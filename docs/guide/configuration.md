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

$telegram = new Telegram($config);
```

---

## Configuration Options

| Method | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `withToken(string $token)` | `string` | `''` | Telegram Bot API token. |
| `withApiServer(string $url)` | `string` | `https://api.telegram.org` | Custom or self-hosted Bot API server. |
| `withTimeout(float $seconds)` | `float` | `30.0` | Maximum timeout for HTTP requests. |
| `withConnectTimeout(float $seconds)` | `float` | `10.0` | Connection timeout for requests. |
| `withProxy(?string $proxy)` | `?string` | `null` | HTTP or SOCKS5 proxy (e.g. `socks5h://127.0.0.1:9050`). |
| `withHttpClient(?HttpClientInterface $client)` | `?HttpClientInterface` | `GuzzleHttpClient` | Custom PSR-18 or HTTP transport client. |
| `withLogger(?LoggerInterface $logger)` | `?LoggerInterface` | `null` | PSR-3 compliant logger for request tracing. |
| `withRetryCount(int $count)` | `int` | `3` | Number of automatic retries on rate limits (429) or transient network errors. |
| `withTestEnvironment(bool $enabled)` | `bool` | `false` | Connects to Telegram's Test Environment (`/test`). |
| `withRunningMode(?RunningModeInterface $mode)` | `?RunningModeInterface` | `WebhookMode` | Active running mode (`WebhookMode` or `PollingMode`). |
| `withErrorHandlingMode(ErrorHandlingMode $mode)` | `ErrorHandlingMode` | `EXCEPTION` | Either `EXCEPTION` or `ERROR_OBJECT`. |
| `withErrorObjectMode(array $catch = [...])` | `array` | `[ApiException::class]` | Shorthand to enable error object mode. |
| `withCatchAllErrors()` | - | - | Catches all `\Throwable` (including network errors) as `Error` objects. |
| `withExceptionMode()` | - | - | Restores default exception-throwing behavior. |

---

## Self-Hosted Bot API Server

If you are running your own local Telegram Bot API server for downloading large files (up to 2GB) or higher request throughput:

```php
$config = Telegram::create('YOUR_BOT_TOKEN')
    ->withApiServer('http://127.0.0.1:8081')
    ->build();

$telegram = new Telegram($config);
```

---

## Test Environment

Telegram maintains a dedicated test server environment for bot developers:

```php
$config = Telegram::create('TEST_BOT_TOKEN')
    ->withTestEnvironment(true)
    ->build();

$telegram = new Telegram($config);
```
All API calls will automatically target `https://api.telegram.org/bot<token>/test/...`.
