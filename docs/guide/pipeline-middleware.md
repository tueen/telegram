# Pipeline & Middleware

## Overview

Every request in `tueen/telegram` passes through an extensible onion pipeline before hitting the HTTP transport.

## Native Middlewares Included

1. **`RetryMiddleware`**:
   Automatically retries requests upon network drops or Telegram 429 Flood Control with exponential backoff and adherence to `retry_after`.
2. **`LoggingMiddleware`**:
   Integrates with any PSR-3 logger (Monolog, etc.) to log endpoint calls, parameters, and execution durations.

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
$telegram = new Telegram('TOKEN');
$telegram->pipe(new CustomHeaderMiddleware());
```
