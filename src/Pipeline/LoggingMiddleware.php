<?php

declare(strict_types=1);

namespace Tueen\Telegram\Pipeline;

use Psr\Log\LoggerInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;

class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ?LoggerInterface $logger = null
    ) {}

    public function handle(Request $request, Config $config, callable $next): Response
    {
        $logger = $this->logger ?? $config->logger;

        if ($logger !== null) {
            $logger->info("Telegram Request: {$request->httpMethod} {$request->endpoint}", [
                'parameters' => array_keys($request->parameters),
                'files' => array_keys($request->files),
            ]);
        }

        $startTime = microtime(true);
        $response = $next($request, $config);
        $elapsed = round((microtime(true) - $startTime) * 1000, 2);

        if ($logger !== null) {
            $logger->info("Telegram Response: {$request->endpoint} ({$elapsed}ms)", [
                'status' => $response->statusCode,
                'ok' => $response->isOk(),
                'error_code' => $response->getErrorCode(),
            ]);
        }

        return $response;
    }
}
