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

#[AsEventListener(event: Events::prePersist, method: 'prePersist', priority: 501)]
final class TimestampAuditPrePersistListener
{
    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof TimestampAuditInterface) {
            return;
        }

        $entity->setCreatedAt(new \DateTimeImmutable());
        $entity->setModifiedAt(new \DateTimeImmutable());
    }
}
