# Error Handling & Lifecycle Hooks

`tueen/telegram` provides two distinct error handling strategies to suit any application style:
1. **Exception-Based (Default):** Throws strongly-typed exceptions (`ApiException`, `RateLimitException`, etc.).
2. **Error-Object Mode:** Returns a typed `Tueen\Telegram\Types\Error` object instead of throwing, checked via universal `ok()` methods.

---

## 1. Universal `ok()` / `isOk()` Check

Every response object produced by the library inherits from [`Type`](/guide/methods-and-types) and provides `ok(): bool` and `isOk(): bool`:

- **Successful Types (`Message`, `User`, `BooleanResult`, etc.):** Always return `true`.
- **`Error` Objects:** Always return `false`.

```php
$res = $telegram->sendMessage(chatId: 12345, text: 'Hello!');

if ($res->ok()) {
    echo "Success! Message ID: {$res->messageId}\n";
} else {
    // $res is an Error object
    echo "Failed [{$res->errorCode}]: {$res->description}\n";
}
```

---

## 2. Error-Object Mode

To prevent API errors from throwing exceptions:

```php
use Tueen\Telegram\Telegram;

$telegram = new Telegram(
    Telegram::create('YOUR_BOT_TOKEN')
        ->withErrorObjectMode()
        ->build()
);

$res = $telegram->sendMessage(chatId: 99999, text: 'Hi');

if (!$res->ok()) {
    // Inspect error details
    echo "Error Code: " . $res->errorCode . "\n";
    echo "Description: " . $res->description . "\n";

    // Extract flood wait timeout if present (429)
    if ($retrySeconds = $res->getRetryAfter()) {
        echo "Flood limit reached. Retry after {$retrySeconds}s\n";
    }

    // Access underlying exception if needed
    $exception = $res->exception;
}
```

---

## 3. Catch All Errors (Including Network Errors)

By default, `withErrorObjectMode()` only converts Telegram `ApiException` responses into `Error` objects.

If you also want connection drops, cURL errors, and network timeouts converted into `Error` objects:

```php
$telegram = new Telegram(
    Telegram::create('YOUR_BOT_TOKEN')
        ->withCatchAllErrors()
        ->build()
);

$res = $telegram->getMe();

if (!$res->ok()) {
    // Network/cURL errors receive negative error codes (e.g. -28)
    echo "Error: {$res->description} (Code: {$res->errorCode})\n";
}
```

---

## 4. Exception-Based Mode (Default)

In standard exception mode, failed API responses throw typed exceptions:

```php
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\NetworkException;

try {
    $telegram->sendMessage(chatId: 12345, text: 'Hello');
} catch (RateLimitException $e) {
    echo "Rate limited! Sleep for {$e->retryAfter} seconds.\n";
} catch (ApiException $e) {
    echo "Telegram API Error [{$e->errorCode}]: {$e->getMessage()}\n";
} catch (NetworkException $e) {
    echo "Network / cURL Failure: {$e->getMessage()}\n";
}
```

---

## 5. Lifecycle Event Hooks

You can register callbacks on the `Telegram` facade to observe or intercept requests and responses:

```php
$telegram = new Telegram('YOUR_BOT_TOKEN');

// 1. Before sending HTTP request
$telegram->onBeforeRequest(function (Request $request, Config $config) {
    // Log outbound request or attach telemetry spans
});

// 2. Immediately after raw HTTP response is received
$telegram->onAfterRequest(function (Response $response, Request $request) {
    // Track HTTP status code and latency
});

// 3. When an error or exception occurs
$telegram->onError(function (Throwable $error, Request $request) {
    // Send alert to Sentry, Bugsnag, or log
});

// 4. When the final result (Type or Error) is created
$telegram->onResponse(function (Type $result, Request $request) {
    // Inspect the resulting Type or Error instance
});
```
