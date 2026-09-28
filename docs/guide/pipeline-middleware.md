# Pipeline & Middleware

## Overview

Every request in `tueen/telegram` passes through an extensible onion pipeline before hitting the HTTP transport.

## Native Middlewares Included

1. **`RetryMiddleware`**:
   Automatically retries requests upon network drops or Telegram 429 Flood Control with exponential backoff and adherence to `retry_after`.
2. **`LoggingMiddleware`**:
   Integrates with any PSR-3 logger (Monolog, etc.) to log endpoint calls, parameters, and execution durations.
3. **`RateLimitMiddleware`**:
   Enforces Telegram's official rate limits locally to prevent `429 Too Many Requests` penalties.
   Features include:
   - **Global Pacing**: Token-bucket algorithm (default: 30 requests/second globally).
   - **Per-Chat Pacing**: Enforces minimum interval between messages to the same chat (default: 1.0 second per chat).
   - **Automatic 429 Retry Backoff**: If Telegram still returns a 429, it automatically sleeps for `retry_after` seconds and replays the request seamlessly.

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Pipeline\RateLimitMiddleware;

$bot = new Telegram('YOUR_BOT_TOKEN');

// Attach rate limiter with default Telegram limits (30 req/sec, 1 sec/chat):
$bot->pipe(new RateLimitMiddleware());

// Or customize limits:
$bot->pipe(new RateLimitMiddleware(
    maxRequestsPerSecond: 25.0,
    chatIntervalSeconds: 1.2,
    autoRetryOn429: true
));
```


## Registering Custom Middleware

Create a class implementing `Tueen\Telegram\Pipeline\MiddlewareInterface`:

```php
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Pipeline\MiddlewareInterface;

class CustomHeaderMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Config $config, callable $next): Response
    {
        // Pre-request logic
        $startTime = hrtime(true);

        $response = $next($request, $config);

        // Post-response logic
        $durationMs = (hrtime(true) - $startTime) / 1e6;
        
        return $response;
    }
}
```

Attach it to your client:

```php
$bot = new Telegram('TOKEN');
$bot->pipe(new CustomHeaderMiddleware());
```
