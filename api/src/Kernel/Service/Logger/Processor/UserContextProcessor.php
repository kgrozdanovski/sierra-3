<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Service\Logger\Processor;

use Monolog\Attribute\AsMonologProcessor;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Monolog processor used to inject additional user context to log messages.
 *
 * Usage in YAML configuration for handlers:
 *      formatter: monolog.formatter.extended_context
 * Usage in YAML configuration for Monolog formatters:
 *      arguments:
 *          - "%%extra.user_public_id%% %%extra.user_roles%%..."
 * @see https://symfony.com/doc/current/logging/processors.html
 *
 * @class RequestContextProcessor
 * @package App\Kernel\Service\Logger
 */
#[AsMonologProcessor]
final readonly class UserContextProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    /**
     * Attach additional request information to the log record.
     *
     * @warn This method is called for each log record - optimize it for performance.
     *
     * @param LogRecord $record
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $user = $this->security->getUser();
        if (empty($user)) {
            return $record;
        }

        // Get Public ID if available, otherwise use the configured User Identifier
        if (method_exists($user, 'getPublicId')) {
            $record->extra['user_id'] = $user->getPublicId();
        } else {
            $record->extra['user_id'] = $user->getUserIdentifier();
        }

        // Attach user role information
        $record->extra['user_roles'] = json_encode($user->getRoles());

        return $record;
    }
}
