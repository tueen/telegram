# Configuration

`tueen/telegram` features an immutable configuration architecture paired with an expressive, fluent `ConfigBuilder`. Every parameter — from networking timeouts and persistent connection pooling to progress event hooks, running modes, and error handling strategies — is strictly typed and discoverable via IDE autocompletion.

---

## 🛠️ 1. Creating Configuration

You can configure the client using `Telegram::create('TOKEN')`, `Config::builder('TOKEN')`, or by instantiating `Config` directly:

### Fluent Builder via `Telegram::create()`
The recommended and most expressive approach:

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Enums\ErrorHandlingMode;
use Tueen\Telegram\Running\WebhookMode;

$config = Telegram::create('123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11')
    ->withTimeout(30.0)
    ->withConnectTimeout(10.0)
    ->withProxy('http://127.0.0.1:10809')
    ->withRetryCount(3)
    ->withErrorObjectMode()
    ->withRunningMode(new WebhookMode(secretToken: 'my_super_secret_token'))
    ->build();

$bot = new Telegram($config);
```

### Direct Instantiation of `Config`
For configuration files or dependency injection factories:

```php
use Tueen\Telegram\Config;

$config = new Config(
    botToken: 'YOUR_BOT_TOKEN',
    timeout: 45.0,
    connectTimeout: 5.0,
    retryCount: 5,
    testEnvironment: false
);

$bot = new Telegram($config);
```

---

## 🧭 2. Detailed Configuration Reference

Below is the exhaustive catalog of all configuration options available on `ConfigBuilder` and `Config`.

### 🌐 Server & Credentials

### 🌐 Server & Credentials

<ApiGroup description="Endpoints, bot tokens, and sandbox testing server flags.">
  <ApiCard
    sig="withToken(string $token)"
    returns="static"
    badge="default: ''"
    desc="Your Telegram Bot API token obtained from @BotFather."
  />
  <ApiCard
    sig="withApiServer(string $url)"
    returns="static"
    badge="default: 'https://api.telegram.org'"
    desc="Custom or self-hosted Telegram Bot API server base URL. Essential for transferring files up to 2GB, higher throughput, or internal networks."
  />
  <ApiCard
    sig="withTestEnvironment(bool $enabled = true)"
    returns="static"
    badge="default: false"
    desc="Switches the target endpoint to Telegram's dedicated Test Server Environment (/test) for sandbox testing."
  />
</ApiGroup>

---

### ⚡ HTTP Client, Networking & Transport

<ApiGroup description="Timeouts, proxies, custom HTTP clients, and persistent connection pooling.">
  <ApiCard
    sig="withTimeout(float $seconds)"
    returns="static"
    badge="default: 30.0"
    desc="Maximum execution timeout for HTTP requests in seconds. For long-polling or large video uploads, consider increasing to 60.0 or higher."
  />
  <ApiCard
    sig="withConnectTimeout(float $seconds)"
    returns="static"
    badge="default: 10.0"
    desc="Connection timeout for opening TCP/TLS sockets to the Telegram API in seconds."
  />
  <ApiCard
    sig="withProxy(?string $proxy)"
    returns="static"
    badge="default: null"
    desc="HTTP, HTTPS, or SOCKS5 proxy URL (e.g. socks5h://127.0.0.1:9050 or http://proxy.corp.internal:8080)."
  />
  <ApiCard
    sig="withHttpClient(?HttpClientInterface $client)"
    returns="static"
    badge="default: GuzzleHttpClient"
    desc="Custom HTTP client implementation implementing HttpClientInterface."
  />
  <ApiCard
    sig="withCurlClient(bool $persistent = true)"
    returns="static"
    badge="default: true"
    desc="High-performance native PHP 8.5 client utilizing persistent cURL share handles (curl_share_init_persistent()). Eliminates repetitive TLS handshakes and DNS lookups."
  />
</ApiGroup>

---

### 📊 Progress Tracking Callbacks

<ApiGroup description="Granular upload and download event listeners for media and documents.">
  <ApiCard
    sig="withUploadProgress(?Closure $callback)"
    returns="static"
    badge="default: null"
    desc="Event callback invoked during file uploads (e.g. sending videos, documents, or photo albums)."
  />
  <ApiCard
    sig="withDownloadProgress(?Closure $callback)"
    returns="static"
    badge="default: null"
    desc="Event callback invoked during file downloads via $bot->downloadFile()."
  />
</ApiGroup>

#### Progress Callback Signature
Both progress callbacks receive three informative parameters:

```php
$config = Telegram::create('YOUR_TOKEN')
    ->withUploadProgress(function (int $bytesUploaded, int $totalBytes, float $percentage): void {
        printf("Uploading: %5.1f%% (%d / %d bytes)\r", $percentage, $bytesUploaded, $totalBytes);
    })
    ->withDownloadProgress(function (int $bytesDownloaded, int $totalBytes, float $percentage): void {
        printf("Downloading: %5.1f%% (%d / %d bytes)\r", $percentage, $bytesDownloaded, $totalBytes);
    })
    ->build();
```

---

### 🛡️ Error Handling Strategy

<ApiGroup description="Dual error modes: traditional exceptions vs. typed Error objects.">
  <ApiCard
    sig="withErrorHandlingMode(ErrorHandlingMode $mode)"
    returns="static"
    badge="default: EXCEPTION"
    desc="Switches between traditional exception throwing (EXCEPTION) and typed Error object returns (ERROR_OBJECT)."
  />
  <ApiCard
    sig="withErrorObjectMode(array $catchExceptions = [ApiException::class])"
    returns="static"
    badge="default: [ApiException::class]"
    desc="Activates error object mode and specifies which exception classes should be converted into returned Error objects."
  />
  <ApiCard
    sig="withExceptionMode()"
    returns="static"
    badge="mode: exception"
    desc="Restores the default exception-throwing behavior for all errors."
  />
  <ApiCard
    sig="withCatchAllErrors()"
    returns="static"
    badge="mode: catch-all"
    desc="Catches all \Throwable instances (including connection resets, DNS failures, and timeout exceptions) as Error objects."
  />
  <ApiCard
    sig="withConvertExceptions(array $classes)"
    returns="static"
    badge="list<class-string<Throwable>>"
    desc="Defines the specific list of exception classes to catch and convert to Error objects."
  />
</ApiGroup>

---

### 🚀 Running Modes & Resiliency

<ApiGroup description="Default bot runner selection, long-polling parameters, webhook security, and retry policies.">
  <ApiCard
    sig="withRunningMode(?RunningModeInterface $mode)"
    returns="static"
    badge="default: WebhookMode"
    desc="Configures the default bot execution runner: new WebhookMode(...), new PollingMode(...), or new AutoMode(...)."
  />
  <ApiCard
    sig="withPollingMode(PollingMode $mode)"
    returns="static"
    badge="PollingMode"
    desc="Convenience shortcut to configure long-polling mode with custom batch limits, timeout, backoff, and process concurrency."
  />
  <ApiCard
    sig="withWebhookMode(WebhookMode $mode)"
    returns="static"
    badge="WebhookMode"
    desc="Convenience shortcut to configure webhook mode with secret token verification and non-blocking safe responses."
  />
  <ApiCard
    sig="withAutoMode(?PollingMode $polling = null, ?WebhookMode $webhook = null, bool $autoDeleteWebhook = false, bool $dropPendingUpdatesOnDelete = false, ?callable $detector = null)"
    returns="static"
    badge="AutoMode"
    desc="Configures adaptive AutoMode to seamlessly switch between CLI Polling and HTTP Webhook execution with optional webhook cleanup and custom environment detection."
  />
  <ApiCard
    sig="withRetryCount(int $count)"
    returns="static"
    badge="default: 3"
    desc="Number of automatic retries on rate limits (429 Too Many Requests with retry_after) or transient network dropouts."
  />
</ApiGroup>

---

### 🔄 Conversation Flow & Orchestration

<ApiGroup description="Root/home flow defaults and update filtering for multi-step flows.">
  <ApiCard
    sig="withRootFlow(?string $flowClass)"
    returns="static"
    badge="?class-string<Flow>"
    desc="Specifies the root/home Flow class. Automatically redirected to on /start, home navigation actions, or as a graceful recovery fallback."
  />
  <ApiCard
    sig="withDefaultFlow(?string $flowClass)"
    returns="static"
    badge="Alias"
    aliasFor="withRootFlow()"
    desc="Shorthand alias for withRootFlow()."
  />
  <ApiCard
    sig="withFlowAllowedUpdates(array $types)"
    returns="static"
    badge="default: [] (all updates)"
    desc="Sets the global default allowed update types for conversation flows. An empty array permits all updates. Disallowed updates bypass the flow."
  />
</ApiGroup>

---

### 📋 Logging & Observability

<ApiGroup description="PSR-3 logger integration for HTTP diagnostics, telemetry, and debugging.">
  <ApiCard
    sig="withLogger(?LoggerInterface $logger)"
    returns="static"
    badge="default: null"
    desc="PSR-3 compliant logger (e.g. Monolog) for logging outbound API requests, response payloads, rate limits, and retries."
  />
</ApiGroup>

---

### 💉 Dependency Injection & Container

<ApiGroup description="PSR-11 container integration for automatic dependency injection.">
  <ApiCard
    sig="withContainer(mixed $container)"
    returns="static"
    badge="PSR-11 / callable"
    desc="PSR-11 container (or callable resolver) used to instantiate controller classes, handler classes in $bot->run(...), and multi-step Flow classes."
  />
</ApiGroup>

---

### ⚡ Client Instantiation Shortcuts

<ApiGroup description="Direct construction shortcuts from the builder.">
  <ApiCard
    sig="client()"
    returns="Telegram"
    badge="Factory"
    desc="Directly constructs and returns an initialized Telegram client instance without having to manually call new Telegram($builder->build())."
  />
  <ApiCard
    sig="make()"
    returns="Telegram"
    badge="Alias"
    aliasFor="client()"
    desc="Shorthand alias for client()."
  />
</ApiGroup>

---

## 🧬 3. Immutability & Modern PHP 8.5 Patterns

The `Config` class is completely immutable. Every `with...()` method creates and returns a clean new instance using PHP 8.5's `clone with` and `#[\NoDiscard]` attributes:

```php
$baseConfig = Telegram::create('YOUR_TOKEN')
    ->withTimeout(30.0)
    ->build();

// Derive a specialized configuration without modifying $baseConfig:
$fastConfig = $baseConfig
    ->withTimeout(5.0)
    ->withRetryCount(1);

// $baseConfig->timeout remains 30.0
// $fastConfig->timeout is 5.0
```

---

## 🌐 4. Standard URI & URL Resolution Methods

`Config` provides convenient methods to resolve target URLs and standards-compliant `Uri\Rfc3986\Uri` objects:

| Method | Return Type | Output Example |
| :--- | :--- | :--- |
| **`getBaseApiUrl()`** | `string` | `https://api.telegram.org/bot<token>` (or `.../bot<token>/test`) |
| **`getBaseFileUrl()`** | `string` | `https://api.telegram.org/file/bot<token>` (or `.../test`) |
| **`getApiUri()`** | `Uri\|string` | RFC 3986 URI instance for the API base |
| **`getFileUri()`** | `Uri\|string` | RFC 3986 URI instance for the File base |

```php
$config = Telegram::create('123456:ABC')->build();

echo $config->getBaseApiUrl();
// "https://api.telegram.org/bot123456:ABC"

$uri = $config->getApiUri();
echo $uri->getHost(); // "api.telegram.org"
echo $uri->getPath(); // "/bot123456:ABC"
```

---

## 💡 5. Real-World Configuration Recipes

### Recipe 1: Production Webhook with Persistent cURL & Monolog

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\WebhookMode;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('telegram');
$logger->pushHandler(new StreamHandler(storage_path('logs/telegram.log')));

$config = Telegram::create($_ENV['TELEGRAM_BOT_TOKEN'])
    // High-performance persistent cURL handles
    ->withCurlClient(persistent: true)
    ->withTimeout(15.0)
    ->withConnectTimeout(3.0)
    ->withRetryCount(3)
    ->withLogger($logger)
    // Production Webhook mode with secret token verification
    ->withRunningMode(new WebhookMode(secretToken: $_ENV['TELEGRAM_WEBHOOK_SECRET']))
    ->build();

$bot = new Telegram($config);
```

---

### Recipe 2: High-Volume CLI Long-Polling Worker

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\PollingMode;

$polling = new PollingMode(
    timeout: 50,             // Long-polling timeout in seconds
    limit: 100,              // Max updates per batch
    errorBackoffSeconds: 5   // Auto-backoff on network interruptions
);

// Enable multi-process concurrency on CLI:
$polling->forkProcess(true);

$config = Telegram::create(getenv('BOT_TOKEN'))
    ->withTimeout(60.0) // Must exceed polling timeout
    ->withRunningMode($polling)
    ->build();

$bot = new Telegram($config);
$bot->run(App\Handlers\UpdateHandler::class);
```

---

### Recipe 3: Tor / SOCKS5 Proxy for Restricted Environments

```php
use Tueen\Telegram\Telegram;

$config = Telegram::create('YOUR_TOKEN')
    ->withProxy('socks5h://127.0.0.1:9050')
    ->withTimeout(60.0)
    ->withConnectTimeout(15.0)
    ->withRetryCount(5)
    ->build();

$bot = new Telegram($config);
```

---

### Recipe 4: Self-Hosted Local Bot API Server (2GB Uploads)

```php
use Tueen\Telegram\Telegram;

$config = Telegram::create('YOUR_TOKEN')
    // Point to your local Telegram Bot API Docker container:
    ->withApiServer('http://telegram-bot-api:8081')
    ->withTimeout(300.0) // 5 minutes for massive files
    ->build();

$bot = new Telegram($config);
```

---

### Recipe 5: Laravel & Symfony PSR-11 Container Integration

```php
use Tueen\Telegram\Telegram;

// In Laravel service provider or Symfony dependency injection:
$config = Telegram::create(config('services.telegram.token'))
    ->withContainer(app()) // Injects Laravel's IoC container
    ->build();

$bot = new Telegram($config);

// Handlers and Flow classes now automatically resolve with full dependency injection!
$bot->run(App\Handlers\OnboardingHandler::class);
```

---

### Recipe 6: Adaptive AutoMode Execution (Unified CLI & HTTP Webhook)

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;

$bot = Telegram::create($_ENV['BOT_TOKEN'])
    ->withPollingMode(new PollingMode(timeout: 45))
    ->withWebhookMode(new WebhookMode(secretToken: $_ENV['WEBHOOK_SECRET']))
    ->withAutoMode(autoDeleteWebhook: true)
    ->client();

// In CLI (php bot.php): executes PollingMode
// In Web Server (POST /webhook): executes WebhookMode
$bot->run(App\Handlers\BotHandler::class);
```
