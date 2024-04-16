<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Kernel\Service\Logger;

use App\Kernel\Communication\HttpFoundation\RequestStackAwareInterface;
use App\Kernel\Communication\HttpFoundation\RequestStackAwareTrait;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Kernel\Symfony\Observability\LoggerContext;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Factory\UuidFactory;

final class Logger implements LoggerInterface
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Security $security,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * Magic method to handle calls to log level methods like info, error, etc.
     *
     * @param string $name Name of the method being called.
     * @param array $arguments Arguments passed to the method.
     */
    public function __call(string $name, array $arguments): void
    {
        // Make sure the method name corresponds to a logging level
        $level = Level::fromName($name);   // Throws an UnhandledMatchError if the level does not exist

        // Extract the message and context from the arguments
        $message = $arguments[0] ?? '';
        $context = $arguments[1] ?? [];

        // Add additional context to the log entry
        $context = $this->buildContext($context);

        // Log the event to Sentry
        // todo...

        // Forward the call to the Monolog logger
        $this->logger->$name($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * Enrich Monolog's context with our extended context.
     *
     * @param array $context
     *
     * @return array
     */
    private function buildContext(array $context): array
    {
        // If not CLI
        if (PHP_SAPI !== 'cli') {
            // Inject Request information
            $context = array_merge($context, $this->getRequestContext());

            // Inject User information
            $context = array_merge($context, $this->getUserContext());
        }

        return $context;
    }

    /**
     * Get the request context.
     *
     * @return array
     */
    private function getRequestContext(): array
    {
        $context = [];

        $request = $this->requestStack->getCurrentRequest();
        if (empty($request)) {
            return $context;
        }

        $context['request'] = [
            'id' => $request->attributes->get('request_id'),
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
        ];

        return $context;
    }

    /**
     * Get the user context.
     *
     * @return array
     */
    private function getUserContext(): array
    {
        $context = [];

        $user = $this->security->getUser();
        if (empty($user)) {
            return $context;
        }

        // Get Public ID if available, otherwise use the User Identifier
        if (method_exists($user, 'getPublicId')) {
            $context['user'] = $user->getPublicId();
        } else {
            $context['user'] = $user->getUserIdentifier();
        }

        return $context;
    }
}
