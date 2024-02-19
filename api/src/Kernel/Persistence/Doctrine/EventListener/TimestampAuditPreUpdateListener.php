<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\EventListener;

use App\Kernel\Persistence\Doctrine\Entity\TimestampAuditInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::preUpdate, method: 'preUpdate')]
final class TimestampAuditPreUpdateListener
{
    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof TimestampAuditInterface) {
            return;
        }

        $entity->setModifiedAt(new \DateTimeImmutable());
    }
}
