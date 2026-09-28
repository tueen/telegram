<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Pipeline\RateLimitMiddleware;

class RateLimitMiddlewareTest extends TestCase
{
    public function testThrottlingDelayBetweenRequests(): void
    {
        // 50 requests per second => 0.02s between requests
        $middleware = new RateLimitMiddleware(maxRequestsPerSecond: 50, minPerChatInterval: 0.01);
        $config = new Config('TEST_TOKEN');
        $request = new Request('sendMessage', ['chat_id' => 123]);

        $start = microtime(true);

        $next = fn($req, $cfg) => new Response(200, ['ok' => true, 'result' => true]);

        $middleware->handle($request, $config, $next);
        $middleware->handle($request, $config, $next);

        $elapsed = microtime(true) - $start;

        // Expect at least 0.015s delay between two requests
        $this->assertGreaterThanOrEqual(0.015, $elapsed);
    }

    public function testHandles429RetryAfterGracefully(): void
    {
        $middleware = new RateLimitMiddleware(
            maxRequestsPerSecond: 100,
            minPerChatInterval: 0.0,
            autoRetryOnRateLimit: true,
            maxRetries: 1
        );
        $config = new Config('TEST_TOKEN');
        $request = new Request('sendMessage');

        $callCount = 0;
        $next = function ($req, $cfg) use (&$callCount) {
            $callCount++;
            if ($callCount === 1) {
                // First attempt returns 429 with retry_after = 0 (for fast unit testing)
                return new Response(429, [
                    'ok' => false,
                    'error_code' => 429,
                    'description' => 'Too Many Requests: retry after 0',
                    'parameters' => ['retry_after' => 0],
                ]);
            }

            return new Response(200, ['ok' => true, 'result' => true]);
        };

        $response = $middleware->handle($request, $config, $next);

        $this->assertSame(2, $callCount);
        $this->assertSame(200, $response->statusCode);
        $this->assertTrue($response->isOk());
    }
}
