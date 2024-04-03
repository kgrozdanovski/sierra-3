<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Service\Logger\EventListener;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 100)]
final readonly class KernelRequestListener
{
    public function __invoke(RequestEvent $event): void
    {
        // Generate a UUID for the current request
        $requestId = Uuid::v4()->toRfc4122();

        // Attach the UUID to the request attributes
        $event->getRequest()->attributes->set('request_id', $requestId);
    }
}
