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

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withToken(string $token)</code>
      <div class="config-card-badges">
        <span class="config-badge type">string</span>
        <span class="config-badge default">default: ''</span>
      </div>
    </div>
    <p class="config-card-desc">Your Telegram Bot API token obtained from <a href="https://t.me/BotFather" target="_blank" rel="noreferrer">@BotFather</a>.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withApiServer(string $url)</code>
      <div class="config-card-badges">
        <span class="config-badge type">string</span>
        <span class="config-badge default">default: 'https://api.telegram.org'</span>
      </div>
    </div>
    <p class="config-card-desc">Custom or self-hosted Telegram Bot API server base URL. Essential for transferring files up to 2GB, higher throughput, or internal networks.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withTestEnvironment(bool $enabled = true)</code>
      <div class="config-card-badges">
        <span class="config-badge type">bool</span>
        <span class="config-badge default">default: false</span>
      </div>
    </div>
    <p class="config-card-desc">Switches the target endpoint to Telegram's dedicated Test Server Environment (<code>/test</code>) for sandbox testing.</p>
  </div>
</div>

---

### ⚡ HTTP Client, Networking & Transport

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withTimeout(float $seconds)</code>
      <div class="config-card-badges">
        <span class="config-badge type">float</span>
        <span class="config-badge default">default: 30.0</span>
      </div>
    </div>
    <p class="config-card-desc">Maximum execution timeout for HTTP requests in seconds. For long-polling or large video uploads, consider increasing to <code>60.0</code> or higher.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withConnectTimeout(float $seconds)</code>
      <div class="config-card-badges">
        <span class="config-badge type">float</span>
        <span class="config-badge default">default: 10.0</span>
      </div>
    </div>
    <p class="config-card-desc">Connection timeout for opening TCP/TLS sockets to the Telegram API in seconds.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withProxy(?string $proxy)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?string</span>
        <span class="config-badge default">default: null</span>
      </div>
    </div>
    <p class="config-card-desc">HTTP, HTTPS, or SOCKS5 proxy URL (e.g. <code>socks5h://127.0.0.1:9050</code> or <code>http://proxy.corp.internal:8080</code>).</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withHttpClient(?HttpClientInterface $client)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?HttpClientInterface</span>
        <span class="config-badge default">default: GuzzleHttpClient</span>
      </div>
    </div>
    <p class="config-card-desc">Custom HTTP client implementation implementing <code>HttpClientInterface</code>.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withCurlClient(bool $persistent = true)</code>
      <div class="config-card-badges">
        <span class="config-badge type">bool</span>
        <span class="config-badge default">default: true</span>
      </div>
    </div>
    <p class="config-card-desc">High-performance native PHP 8.5 client utilizing persistent cURL share handles (<code>curl_share_init_persistent()</code>). Eliminates repetitive TLS handshakes and DNS lookups.</p>
  </div>
</div>

---

### 📊 Progress Tracking Callbacks

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withUploadProgress(?Closure $callback)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?Closure</span>
        <span class="config-badge default">default: null</span>
      </div>
    </div>
    <p class="config-card-desc">Event callback invoked during file uploads (e.g. sending videos, documents, or photo albums).</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withDownloadProgress(?Closure $callback)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?Closure</span>
        <span class="config-badge default">default: null</span>
      </div>
    </div>
    <p class="config-card-desc">Event callback invoked during file downloads via <code>$bot->downloadFile()</code>.</p>
  </div>
</div>

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

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withErrorHandlingMode(ErrorHandlingMode $mode)</code>
      <div class="config-card-badges">
        <span class="config-badge type">ErrorHandlingMode</span>
        <span class="config-badge default">default: EXCEPTION</span>
      </div>
    </div>
    <p class="config-card-desc">Switches between traditional exception throwing (<code>EXCEPTION</code>) and typed <code>Error</code> object returns (<code>ERROR_OBJECT</code>).</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withErrorObjectMode(array $catchExceptions = [ApiException::class])</code>
      <div class="config-card-badges">
        <span class="config-badge type">array</span>
        <span class="config-badge default">default: [ApiException::class]</span>
      </div>
    </div>
    <p class="config-card-desc">Activates error object mode and specifies which exception classes should be converted into returned <code>Error</code> objects.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withExceptionMode()</code>
      <div class="config-card-badges">
        <span class="config-badge default">mode: exception</span>
      </div>
    </div>
    <p class="config-card-desc">Restores the default exception-throwing behavior for all errors.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withCatchAllErrors()</code>
      <div class="config-card-badges">
        <span class="config-badge default">mode: catch-all</span>
      </div>
    </div>
    <p class="config-card-desc">Catches all <code>\Throwable</code> instances (including connection resets, DNS failures, and timeout exceptions) as <code>Error</code> objects.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withConvertExceptions(array $classes)</code>
      <div class="config-card-badges">
        <span class="config-badge type">list&lt;class-string&lt;Throwable&gt;&gt;</span>
      </div>
    </div>
    <p class="config-card-desc">Defines the specific list of exception classes to catch and convert to <code>Error</code> objects.</p>
  </div>
</div>

---

### 🚀 Running Modes & Resiliency

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withRunningMode(?RunningModeInterface $mode)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?RunningModeInterface</span>
        <span class="config-badge default">default: WebhookMode</span>
      </div>
    </div>
    <p class="config-card-desc">Configures the default bot execution runner: <code>new WebhookMode(...)</code>, <code>new PollingMode(...)</code>, or <code>new AutoMode(...)</code>.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withAutoMode(?PollingMode $polling, ?WebhookMode $webhook, bool $autoDeleteWebhook = false)</code>
      <div class="config-card-badges">
        <span class="config-badge type">AutoMode</span>
        <span class="config-badge default">default: Polling in CLI / Webhook in HTTP</span>
      </div>
    </div>
    <p class="config-card-desc">Configures adaptive <code>AutoMode</code> to seamlessly switch between CLI Polling and HTTP Webhook execution.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withRetryCount(int $count)</code>
      <div class="config-card-badges">
        <span class="config-badge type">int</span>
        <span class="config-badge default">default: 3</span>
      </div>
    </div>
    <p class="config-card-desc">Number of automatic retries on rate limits (<code>429 Too Many Requests</code> with <code>retry_after</code>) or transient network dropouts.</p>
  </div>
</div>

---

### 📋 Logging & Observability

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withLogger(?LoggerInterface $logger)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?LoggerInterface</span>
        <span class="config-badge default">default: null</span>
      </div>
    </div>
    <p class="config-card-desc">PSR-3 compliant logger (e.g. Monolog) for logging outbound API requests, response payloads, rate limits, and retries.</p>
  </div>
</div>

---

### 💉 Dependency Injection & Container

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withContainer(mixed $container)</code>
      <div class="config-card-badges">
        <span class="config-badge type">mixed (PSR-11 / callable)</span>
        <span class="config-badge default">default: null</span>
      </div>
    </div>
    <p class="config-card-desc">PSR-11 container (or callable resolver) used to instantiate controller classes, handler classes in <code>$bot->run(...)</code>, and multi-step <code>Flow</code> classes.</p>
  </div>
</div>

---

## 📑 3. Complete Reference Table

| Property on `Config` | PHP Type | Default | ConfigBuilder Method | Description |
| :--- | :--- | :--- | :--- | :--- |
| **`botToken`** | `string` | `''` | `withToken()` | Telegram Bot API token |
| **`apiServer`** | `string` | `'https://api.telegram.org'` | `withApiServer()` | Base API endpoint URL |
| **`timeout`** | `float` | `30.0` | `withTimeout()` | HTTP request execution timeout (seconds) |
| **`connectTimeout`** | `float` | `10.0` | `withConnectTimeout()` | TCP/TLS connection timeout (seconds) |
| **`proxy`** | `?string` | `null` | `withProxy()` | HTTP or SOCKS5 proxy URL |
| **`httpClient`** | `?HttpClientInterface` | `null` (Guzzle) | `withHttpClient()`, `withCurlClient()` | HTTP transport client |
| **`logger`** | `?LoggerInterface` | `null` | `withLogger()` | PSR-3 compliant logger instance |
| **`uploadProgress`** | `?Closure` | `null` | `withUploadProgress()` | Upload progress callback |
| **`downloadProgress`** | `?Closure` | `null` | `withDownloadProgress()` | Download progress callback |
| **`retryCount`** | `int` | `3` | `withRetryCount()` | Automatic retry attempts on 429/network errors |
| **`testEnvironment`** | `bool` | `false` | `withTestEnvironment()` | Target Telegram's `/test` sandbox |
| **`errorHandlingMode`** | `ErrorHandlingMode` | `EXCEPTION` | `withErrorHandlingMode()` | EXCEPTION vs ERROR_OBJECT |
| **`convertExceptionsToError`** | `list<class-string<Throwable>>` | `[ApiException::class]` | `withConvertExceptions()` | Exceptions converted into `Error` objects |
| **`runningMode`** | `?RunningModeInterface` | `null` (Webhook) | `withRunningMode()`, `withAutoMode()` | WebhookMode, PollingMode, or AutoMode |
| **`container`** | `mixed` | `null` | `withContainer()` | PSR-11 container for dependency resolution |

---

## 🧬 4. Immutability & Modern PHP 8.5 Patterns

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

## 🌐 5. Standard URI & URL Resolution Methods

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

## 💡 6. Real-World Configuration Recipes

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
