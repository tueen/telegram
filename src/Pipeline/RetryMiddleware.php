<?php

declare(strict_types=1);

namespace Tueen\Telegram\Pipeline;

use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\NetworkException;

class RetryMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly int $maxRetries = 3,
        private readonly int $maxRetryWaitSeconds = 60
    ) {}

    #[\Override]
    public function handle(Request $request, Config $config, callable $next): Response
    {
        $attempts = 0;
        $max = max(1, $this->maxRetries ?: $config->retryCount);

        while (true) {
            $attempts++;
            try {
                $response = $next($request, $config);

                // Check for 429 Too Many Requests
                if (($response->errorCode === 429 || $response->statusCode === 429) && $attempts < $max) {
                    $retryAfter = (int)($response->parameters['retry_after'] ?? 1);
                    sleep(min($retryAfter, $this->maxRetryWaitSeconds));
                    continue;
                }

                // Check for temporary Telegram server errors (500, 502, 503, 504)
                if (in_array($response->statusCode, [500, 502, 503, 504], true) && $attempts < $max) {
                    usleep(500000 * $attempts); // 0.5s exponential backoff
                    continue;
                }

                return $response;
            } catch (NetworkException $e) {
                if ($attempts >= $max) {
                    throw $e;
                }
                usleep(500000 * $attempts); // 0.5s exponential backoff
            }
        }
    }
}
