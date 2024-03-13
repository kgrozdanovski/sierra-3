<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use App\Kernel\Business\Transformer\CastTransformer;
use App\Kernel\Service\Logger\Logger;
use App\Kernel\Persistence\Doctrine\Audit\Reflector\EntityAttributeReflector;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Uid\Uuid;

#[AsEventListener(event: Events::postUpdate, method: 'postUpdate')]
final readonly class EntityAuditPostUpdateListener
{
    public function __construct(
        private EntityAttributeReflector $entityAttributeReflector,
        private CastTransformer $castTransformer,
        private Security $security,
        private Logger $logger
    ) {}

    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        $auditEntityName = $this->entityAttributeReflector->getName($entity);

        if ($auditEntityName === null) {
            return;
        }

        $unitOfWork = $args->getObjectManager()->getUnitOfWork();
        $entityChangeSet = $unitOfWork->getEntityChangeSet($entity);

        // Do not log changes to the metadata columns `modified_at` and `modified_by`
        if (array_key_exists('modifiedAt', $entityChangeSet)) {
            unset($entityChangeSet['modifiedAt']);
        }
        if (array_key_exists('modifiedBy', $entityChangeSet)) {
            unset($entityChangeSet['modifiedBy']);
        }

        foreach ($entityChangeSet as $propertyName => $changeSet) {
            $originalValueString = $this->castTransformer->toString($changeSet[0]);
            $updatedValueString  = $this->castTransformer->toString($changeSet[1]);

            if (!is_string($originalValueString) || !is_string($updatedValueString)) {
                continue;
            }

            // todo: get request id from request
            $requestId = Uuid::fromString('9adf9850-c657-4776-a8bd-4a2e13cc9555');

            $auditEntity = (new $auditEntityName)
                ->setRequestId($requestId)
                ->setEntity($entity)
                ->setPropertyName($propertyName)
                ->setOriginalValueString($originalValueString)
                ->setUpdatedValueString($updatedValueString)
                ->setCreatedBy($this->security->getUser())
                ->setCreatedAt(new \DateTimeImmutable());

            $args->getObjectManager()->persist($auditEntity);
        }

        $args->getObjectManager()->flush();
    }
}
