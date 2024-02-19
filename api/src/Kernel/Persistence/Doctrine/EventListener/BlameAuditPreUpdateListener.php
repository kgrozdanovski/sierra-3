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
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::preUpdate, method: 'preUpdate')]
final readonly class BlameAuditPreUpdateListener
{
    public function __construct(
        private UserBuilder $userBuilder
    ) {}

    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof BlameAuditInterface) {
            return;
        }

        $entity->setModifiedBy($this->userBuilder->build());
    }
}
