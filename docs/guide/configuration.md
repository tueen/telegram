# Configuration

`tueen/telegram` provides an immutable `Config` object accompanied by a fluent `ConfigBuilder` to configure every aspect of the client cleanly.

---

## Creating Configuration

You can configure the client using `Telegram::create('TOKEN')`:

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
    ->withRunningMode(new WebhookMode(secretToken: 'my_secret'))
    ->build();

$bot = new Telegram($config);
```

---

## Configuration Options

The `ConfigBuilder` provides a chain of fluent methods to configure all client settings. Each option is designed to be self-contained and chainable.

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
    <p class="config-card-desc">Telegram Bot API token obtained from <a href="https://t.me/BotFather" target="_blank" rel="noreferrer">@BotFather</a>.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withApiServer(string $url)</code>
      <div class="config-card-badges">
        <span class="config-badge type">string</span>
        <span class="config-badge default">default: 'https://api.telegram.org'</span>
      </div>
    </div>
    <p class="config-card-desc">Custom or self-hosted Telegram Bot API server URL for large file transfers (up to 2GB) or local networks.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withTestEnvironment(bool $enabled = true)</code>
      <div class="config-card-badges">
        <span class="config-badge type">bool</span>
        <span class="config-badge default">default: false</span>
      </div>
    </div>
    <p class="config-card-desc">Switches the target endpoint to Telegram's dedicated Test Server Environment (<code>/test</code>).</p>
  </div>
</div>

### ⚡ HTTP Client & Networking

<div class="config-group">
  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withTimeout(float $seconds)</code>
      <div class="config-card-badges">
        <span class="config-badge type">float</span>
        <span class="config-badge default">default: 30.0</span>
      </div>
    </div>
    <p class="config-card-desc">Maximum execution timeout for HTTP requests in seconds.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withConnectTimeout(float $seconds)</code>
      <div class="config-card-badges">
        <span class="config-badge type">float</span>
        <span class="config-badge default">default: 10.0</span>
      </div>
    </div>
    <p class="config-card-desc">Connection timeout for opening sockets to the Telegram API in seconds.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withProxy(?string $proxy)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?string</span>
        <span class="config-badge default">default: null</span>
      </div>
    </div>
    <p class="config-card-desc">HTTP, HTTPS, or SOCKS5 proxy URL (e.g. <code>socks5h://127.0.0.1:9050</code> or <code>http://proxy:8080</code>).</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withHttpClient(?HttpClientInterface $client)</code>
      <div class="config-card-badges">
        <span class="config-badge type">?HttpClientInterface</span>
        <span class="config-badge default">default: GuzzleHttpClient</span>
      </div>
    </div>
    <p class="config-card-desc">Custom PSR-18 or HTTP transport client implementation.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withCurlClient(bool $persistent = true)</code>
      <div class="config-card-badges">
        <span class="config-badge type">bool</span>
        <span class="config-badge default">default: true</span>
      </div>
    </div>
    <p class="config-card-desc">High-performance native PHP 8.5 client utilizing persistent cURL share handles (<code>curl_share_init_persistent()</code>).</p>
  </div>
</div>

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
    <p class="config-card-desc">Sets the active bot execution mode (<code>WebhookMode</code> or <code>PollingMode</code>).</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withRetryCount(int $count)</code>
      <div class="config-card-badges">
        <span class="config-badge type">int</span>
        <span class="config-badge default">default: 3</span>
      </div>
    </div>
    <p class="config-card-desc">Number of automatic retries on rate limits (<code>429 Too Many Requests</code>) or transient network errors.</p>
  </div>
</div>

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
    <p class="config-card-desc">Chooses between standard exception throwing (<code>EXCEPTION</code>) and typed error objects (<code>ERROR_OBJECT</code>).</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withErrorObjectMode(array $catch = [...])</code>
      <div class="config-card-badges">
        <span class="config-badge type">array</span>
        <span class="config-badge default">default: [ApiException::class]</span>
      </div>
    </div>
    <p class="config-card-desc">Convenience shortcut to activate error object mode for specified exception classes without throwing.</p>
  </div>

  <div class="config-card">
    <div class="config-card-header">
      <code class="method-name">withCatchAllErrors()</code>
      <div class="config-card-badges">
        <span class="config-badge default">mode: catch-all</span>
      </div>
    </div>
    <p class="config-card-desc">Catches all <code>\Throwable</code> exceptions (including network drops and timeouts) as <code>Error</code> objects.</p>
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
</div>

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
    <p class="config-card-desc">PSR-3 compliant logger for tracing HTTP requests, rate limits, retries, and errors.</p>
  </div>
</div>

---

## Modern Architecture

### 1. Fluent Immutable Config
The `Config` object is completely immutable. You can create modified configuration copies fluently using `with...` methods without rebuilding from scratch:

```php
$config = new Config('YOUR_TOKEN', timeout: 30.0);

// Returns a new immutable Config instance:
$fastConfig = $config->withTimeout(5.0)->withRetryCount(1);
```

### 2. High-Performance Native cURL Client
PHP 8.5 introduces `curl_share_init_persistent()`. `tueen/telegram` includes `CurlHttpClient` which eliminates repetitive TLS handshakes and DNS lookups across requests:

```php
$config = Telegram::create('YOUR_TOKEN')
    ->withCurlClient(persistent: true)
    ->build();
```

### 3. Standards-Compliant `Uri\Rfc3986\Uri`
Config exposes standard URI representations powered by PHP 8.5's native URI extension:

```php
$apiUri = $config->getApiUri();
echo $apiUri->getHost(); // api.telegram.org
echo $apiUri->getPath(); // /bot<token>
```

---

## Self-Hosted Bot API Server

If you are running your own local Telegram Bot API server for downloading large files (up to 2GB) or higher request throughput:

```php
$config = Telegram::create('YOUR_BOT_TOKEN')
    ->withApiServer('http://127.0.0.1:8081')
    ->build();

$bot = new Telegram($config);
```

---

## Test Environment

Telegram maintains a dedicated test server environment for bot developers:

```php
$config = Telegram::create('TEST_BOT_TOKEN')
    ->withTestEnvironment(true)
    ->build();

$bot = new Telegram($config);
```
All API calls will automatically target `https://api.telegram.org/bot<token>/test/...`.
