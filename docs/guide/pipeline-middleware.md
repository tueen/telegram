# Pipeline & Middlewares

Every outbound API request in `tueen/telegram` passes through an extensible onion middleware pipeline before reaching the underlying HTTP transport. This allows you to transparently handle retries, rate limits, request logging, tracing, and metric collection.

---

## ⚡ 1. Native Built-In Middlewares

`tueen/telegram` includes three battle-tested middlewares out of the box:

### 1. `RetryMiddleware`
Automatically retries requests upon network dropouts or Telegram `429 Too Many Requests` penalties with exponential backoff and strict adherence to Telegram's `retry_after` header.

### 2. `RateLimitMiddleware`
Enforces Telegram's official rate limits locally using an in-memory token-bucket algorithm, preventing rate limit penalties before they occur:
- **Global Pacing:** Default 30 requests per second across all chats.
- **Per-Chat Pacing:** Enforces minimum interval between consecutive messages to the same chat (default: 1.0 second per chat).
- **Automatic 429 Sleep Backoff:** If Telegram still responds with 429, sleeps for `retry_after` seconds and replays automatically.

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Pipeline\RateLimitMiddleware;

$bot = new Telegram('YOUR_BOT_TOKEN');

// Attach rate limiter with default Telegram limits (30 req/sec, 1 sec/chat):
$bot->pipe(new RateLimitMiddleware());

// Or customize limits for high-volume broadcast bots:
$bot->pipe(new RateLimitMiddleware(
    maxRequestsPerSecond: 25.0,
    chatIntervalSeconds: 1.2,
    autoRetryOn429: true
));
```

### 3. `LoggingMiddleware`
Integrates with any PSR-3 logger (Monolog, Laravel Log, etc.) to log endpoint calls, parameters, and execution durations:

```php
use Tueen\Telegram\Pipeline\LoggingMiddleware;

$bot->pipe(new LoggingMiddleware($logger));
```

---

## 🛠️ 2. Authoring Custom Middleware

Create a class implementing `Tueen\Telegram\Pipeline\MiddlewareInterface`:

```php
namespace App\Middleware;

use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Pipeline\MiddlewareInterface;

class ExecutionTimerMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Config $config, callable $next): Response
    {
        // 1. Pre-request hook
        $startTime = hrtime(true);

        // 2. Delegate to next middleware in onion pipeline
        $response = $next($request, $config);

        // 3. Post-response hook
        $durationMs = (hrtime(true) - $startTime) / 1e6;
        error_log("API call [{$request->method}] completed in {$durationMs}ms");

        return $response;
    }
}
```

Attach your custom middleware using `$bot->pipe(...)`:

```php
$bot->pipe(new ExecutionTimerMiddleware());
```

---

## 🧭 3. Pipeline API Catalog

Below is the complete reference of built-in pipeline middlewares and registration methods.

### 🧱 Pipeline Middlewares (`Tueen\Telegram\Pipeline\`)

<ApiGroup description="Extensible middlewares for outbound Telegram Bot API requests.">
  <ApiCard
    sig="RetryMiddleware::__construct(int $maxRetries = 3, float $initialDelay = 1.0, float $multiplier = 2.0)"
    returns="RetryMiddleware"
    badge="Middleware"
    desc="Exponential backoff retry middleware with automatic retry_after parsing."
  />
  <ApiCard
    sig="RateLimitMiddleware::__construct(float $maxRequestsPerSecond = 30.0, float $chatIntervalSeconds = 1.0, bool $autoRetryOn429 = true)"
    returns="RateLimitMiddleware"
    badge="Middleware"
    desc="Token-bucket rate limiter enforcing local pacing per-second and per-chat."
  />
  <ApiCard
    sig="LoggingMiddleware::__construct(LoggerInterface $logger, string $level = 'info')"
    returns="LoggingMiddleware"
    badge="Middleware"
    desc="PSR-3 request/response logger with latency measurements and sanitized parameters."
  />
  <ApiCard
    sig="pipe(MiddlewareInterface|Closure $middleware): static"
    returns="static"
    badge="Pipeline"
    desc="Appends a middleware class instance or closure into the client's outbound request pipeline."
  />
</ApiGroup>
