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
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Monolog processor used to inject additional request context to log messages.
 *
 * Usage in YAML configuration for handlers:
 *      formatter: monolog.formatter.request_context
 * Usage in YAML configuration for Monolog formatters:
 *      arguments:
 *          - "%%extra.request_id%% %%extra.request_method%%..."
 * @see https://symfony.com/doc/current/logging/processors.html
 *
 * @class RequestContextProcessor
 * @package App\Kernel\Service\Logger
 */
#[AsMonologProcessor]
final readonly class RequestContextProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly RequestStack $requestStack
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
        $request = $this->requestStack->getCurrentRequest();
        if (empty($request)) {
            return $record;
        }

        $record->extra['request_id'] = $request->attributes->get('request_id');
        $record->extra['request_method'] = $request->getMethod();
        $record->extra['request_uri'] = $request->getUri();
        $record->extra['client_ip'] = $request->getClientIp();
        $record->extra['user_agent'] = $request->headers->get('User-Agent');

        return $record;
    }
}
