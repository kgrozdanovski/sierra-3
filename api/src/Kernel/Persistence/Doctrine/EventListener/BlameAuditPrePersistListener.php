<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\EventListener;

use App\Kernel\Business\Security\Builder\UserBuilder;
use App\Kernel\Persistence\Doctrine\Entity\BlameAuditInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::prePersist, method: 'prePersist', priority: 501)]
final readonly class BlameAuditPrePersistListener
{
    public function __construct(
        private UserBuilder $userBuilder
    ) {
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof BlameAuditInterface) {
            return;
        }

        $user = $this->userBuilder->build();

        $entity->setCreatedBy($user);
        $entity->setModifiedBy($user);
    }
}
