<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Kernel\Symfony\EventSubscriber;

use App\Kernel\Communication\HttpFoundation\Response\ApiResponse;
use App\Kernel\Communication\HttpFoundation\Type\StatusCode;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use App\Kernel\Service\Logger\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Kernel\Communication\HttpKernel\Exception\ValidationException;

class ExceptionEventHandler implements EventSubscriberInterface
{
    public function __construct(
        protected Logger $logger,
        protected EntityManagerInterface $entityManager
    ) {
//        $this->logger = $logger->withChannel(self::class);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['handle', 0]
        ];
    }

    /**
     * Handle unhandled exceptions.
     *
     * @param ExceptionEvent $event
     * @throws Exception
     */
    public function handle(ExceptionEvent $event): void
    {
        // Clean-up potentially forgotten database transactions
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->rollBack();
            $this->entityManager->getConnection()->close();
        }

        $response = new ApiResponse();
        $throwable = $event->getThrowable();

        // Handle various exceptions and respond accordingly
        switch (get_class($throwable)) {
            // 400
            case BadRequestHttpException::class:
            case \App\Kernel\Communication\HttpKernel\Exception\BadRequestHttpException::class:
                $eventResponse = $response
                    ->setStatusCode(StatusCode::BAD_REQUEST)
                    ->setMessage($throwable->getMessage())
                    ->render();

                $event->setResponse($eventResponse);
                return;
            // 401
            case UnauthorizedHttpException::class:
                $eventResponse = $response
                    ->setStatusCode(StatusCode::UNAUTHORIZED)
                    ->setMessage($throwable->getMessage())
                    ->render();

                $event->setResponse($eventResponse);
                return;
            // 403
            case AccessDeniedHttpException::class:
                $eventResponse = $response
                    ->setStatusCode(StatusCode::FORBIDDEN)
                    ->render();

                $event->setResponse($eventResponse);
//                $this->logger->notice($throwable->getMessage(), LogCtx::throwable($throwable));
                return;
            // 404
            case NotFoundHttpException::class:
                $eventResponse = $response
                    ->setStatusCode(StatusCode::NOT_FOUND)
                    ->setMessage(urldecode($event->getRequest()->getPathInfo()))
                    ->render();

                $event->setResponse($eventResponse);
                return;
            case ResourceNotFoundException::class:
                $eventResponse = $response
                    ->setStatusCode(StatusCode::NOT_FOUND)
                    ->setMessage('Resource not found')
                    ->render();

                $event->setResponse($eventResponse);
                return;
            // 405
            case MethodNotAllowedHttpException::class:
                $eventResponse = $response
                    ->setStatusCode(StatusCode::METHOD_NOT_ALLOWED)
                    ->render();

                $event->setResponse($eventResponse);
                return;
            // 422, validation failed
            case ValidationException::class:
                $messages = $this->processNestedMessages($throwable);

                $eventResponse = $response
                    ->setStatusCode(StatusCode::VALIDATION_FAILED)
                    ->setMessage('Validation failed')
                    ->setData(['errors' => $messages])
                    ->render();

                $event->setResponse($eventResponse);
//                $this->logger->notice($throwable->getMessage(), LogCtx::throwable($throwable));
                return;
            // 500
            default:
                $message = $this->getDescriptionFromThrowable($throwable);

                // Include extra data in development environments for quicker debugging
                if ($_ENV['APP_DEBUG']) {
                    $response->setData([
                        'error' => $message,
                        'trace' => $throwable->getTrace()
                    ]);
                }

                // Message should not be leaked to HTTP client for security reasons, only logged
                $eventResponse = $response
                    ->setStatusCode(StatusCode::INTERNAL_ERROR)
                    ->render();

                $event->setResponse($eventResponse);
//                $this->logger->emergency('Unhandled throwable: ' . $throwable->getMessage(), $logContext);
        }
    }

    /**
     * Flatten out nested error key-value array messages
     *
     * @param \Throwable $throwable
     * @return array|string
     */
    private static function processNestedMessages(\Throwable $throwable): array|string
    {
        $unpackedMessages = [];

        do {
            $message = json_decode($throwable->getMessage(), true);

            if (is_array($message)) {
                foreach ($message as $errorKey => $errorValue) {
                    // Assume keys will be unique (use case is Validator functions)
                    $unpackedMessages[$errorKey] = $errorValue;
                }
            }
        } while ($throwable->getPrevious());

        // If there were no nested errors, return the original array
        if (!empty($unpackedMessages)) {
            return $unpackedMessages;
        }

        return $message;
    }

    /**
     * @param \Throwable $throwable
     *
     * @return string
     */
    private function getDescriptionFromThrowable(\Throwable $throwable): string
    {
        return $throwable->getMessage() . ' in ' . $throwable->getFile() . ', line ' . $throwable->getLine();
    }
}
