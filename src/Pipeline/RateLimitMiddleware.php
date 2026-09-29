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

    /** @var (callable(string, ?float): ?float)|null */
    private mixed $timeStore = null;

    /** @var (callable(float): void)|null */
    private mixed $sleeper = null;

    public function __construct(
        int $maxRequestsPerSecond = 30,
        private float $minPerChatInterval = 1.0,
        private bool $autoRetryOnRateLimit = true,
        private int $maxRetries = 2,
        ?callable $timeStore = null,
        ?callable $sleeper = null
    ) {
        $this->minInterval = $maxRequestsPerSecond > 0 ? (1.0 / $maxRequestsPerSecond) : 0.0;
        $this->timeStore = $timeStore;
        $this->sleeper = $sleeper;
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
                    $retryAfter = (float)($response->data['parameters']['retry_after'] ?? 1);
                    if ($retryAfter > 0) {
                        $this->sleepSeconds($retryAfter);
                    }
                    continue;
                }

                return $response;
            } catch (ApiException $e) {
                if ($e->hasResponseParameters() && $e->responseParameters?->retryAfter !== null && $this->autoRetryOnRateLimit && $attempts < $this->maxRetries) {
                    $attempts++;
                    $retryAfter = (float)$e->responseParameters->retryAfter;
                    if ($retryAfter > 0) {
                        $this->sleepSeconds($retryAfter);
                    }
                    continue;
                }

                throw $e;
            }
        }
    }

    private function sleepSeconds(float $seconds): void
    {
        if ($seconds <= 0.0) {
            return;
        }

        if ($this->sleeper !== null) {
            ($this->sleeper)($seconds);
            return;
        }

        usleep((int)($seconds * 1_000_000));
    }

    /** @var array<string|int, float> */
    private array $lastChatRequestTimes = [];
    private int $requestCounter = 0;

    private function pruneChatTimes(float $now): void
    {
        // Periodic cleanup every 100 requests, or if array grows above 1,000 items
        if (++$this->requestCounter < 100 && count($this->lastChatRequestTimes) < 1000) {
            return;
        }

        $this->requestCounter = 0;
        // Purge any chat that hasn't made a request in the last 60 seconds
        $cutoff = $now - 60.0;
        foreach ($this->lastChatRequestTimes as $id => $time) {
            if ($time < $cutoff) {
                unset($this->lastChatRequestTimes[$id]);
            }
        }
    }

    private function throttle(Request $request): void
    {
        $now = microtime(true);
        $this->pruneChatTimes($now);

        // 1. Global throttle
        $lastGlobal = $this->timeStore !== null
            ? ($this->timeStore)('global', null)
            : $this->lastRequestTime;

        if ($this->minInterval > 0 && $lastGlobal !== null && $lastGlobal > 0) {
            $elapsed = $now - $lastGlobal;
            if ($elapsed < $this->minInterval) {
                $this->sleepSeconds($this->minInterval - $elapsed);
            }
        }

        $nowAfterGlobal = microtime(true);
        if ($this->timeStore !== null) {
            ($this->timeStore)('global', $nowAfterGlobal);
        } else {
            $this->lastRequestTime = $nowAfterGlobal;
        }

        // 2. Per-chat throttle
        $chatId = $request->parameters['chat_id'] ?? null;
        if ($chatId !== null && $this->minPerChatInterval > 0) {
            $chatKey = "chat:{$chatId}";
            $lastChat = $this->timeStore !== null
                ? ($this->timeStore)($chatKey, null)
                : ($this->lastChatRequestTimes[$chatId] ?? null);

            if ($lastChat !== null && $lastChat > 0) {
                $chatElapsed = microtime(true) - $lastChat;
                if ($chatElapsed < $this->minPerChatInterval) {
                    $this->sleepSeconds($this->minPerChatInterval - $chatElapsed);
                }
            }

            $nowAfterChat = microtime(true);
            if ($this->timeStore !== null) {
                ($this->timeStore)($chatKey, $nowAfterChat);
            } else {
                $this->lastChatRequestTimes[$chatId] = $nowAfterChat;
            }
        }
    }
}
