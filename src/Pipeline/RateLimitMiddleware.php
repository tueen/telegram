<?php

declare(strict_types=1);

namespace Tueen\Telegram\Pipeline;

use Closure;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\ApiException;

/**
 * Middleware enforcing Telegram Bot API rate limits and automatically handling HTTP 429 retries.
 *
 * Prevents "Too Many Requests" by pacing outgoing requests globally and per-chat,
 * and transparently retries requests when Telegram specifies 'retry_after'.
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    private float $minInterval;
    private float $lastRequestTime = 0.0;

    /** @var array<string|int, float> */
    private array $lastChatRequestTimes = [];

    public function __construct(
        int $maxRequestsPerSecond = 30,
        private float $minPerChatInterval = 0.05,
        private bool $autoRetryOnRateLimit = true,
        private int $maxRetries = 2
    ) {
        $this->minInterval = $maxRequestsPerSecond > 0 ? (1.0 / $maxRequestsPerSecond) : 0.0;
    }

    public function handle(Request $request, Config $config, callable $next): Response
    {
        $attempts = 0;

        while (true) {
            $this->throttle($request);

            try {
                $response = $next($request, $config);

                if ($response->statusCode === 429 && $this->autoRetryOnRateLimit && $attempts < $this->maxRetries) {
                    $attempts++;
                    $retryAfter = (int)($response->data['parameters']['retry_after'] ?? 1);
                    if ($retryAfter > 0) {
                        sleep($retryAfter);
                    }
                    continue;
                }

                return $response;
            } catch (ApiException $e) {
                if ($e->hasResponseParameters() && $e->responseParameters?->retryAfter !== null && $this->autoRetryOnRateLimit && $attempts < $this->maxRetries) {
                    $attempts++;
                    $retryAfter = $e->responseParameters->retryAfter;
                    if ($retryAfter > 0) {
                        sleep($retryAfter);
                    }
                    continue;
                }

                throw $e;
            }
        }
    }

    private function throttle(Request $request): void
    {
        $now = microtime(true);

        // 1. Global throttle
        if ($this->minInterval > 0 && $this->lastRequestTime > 0) {
            $elapsed = $now - $this->lastRequestTime;
            if ($elapsed < $this->minInterval) {
                $sleepMicro = (int)(($this->minInterval - $elapsed) * 1_000_000);
                if ($sleepMicro > 0) {
                    usleep($sleepMicro);
                }
            }
        }
        $this->lastRequestTime = microtime(true);

        // 2. Per-chat throttle
        $chatId = $request->parameters['chat_id'] ?? null;
        if ($chatId !== null && $this->minPerChatInterval > 0) {
            if (isset($this->lastChatRequestTimes[$chatId])) {
                $chatElapsed = microtime(true) - $this->lastChatRequestTimes[$chatId];
                if ($chatElapsed < $this->minPerChatInterval) {
                    $sleepMicro = (int)(($this->minPerChatInterval - $chatElapsed) * 1_000_000);
                    if ($sleepMicro > 0) {
                        usleep($sleepMicro);
                    }
                }
            }
            $this->lastChatRequestTimes[$chatId] = microtime(true);
        }
    }
}
