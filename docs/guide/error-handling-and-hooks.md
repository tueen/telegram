# Error Handling & Lifecycle Hooks

`tueen/telegram` provides a comprehensive, type-safe error handling architecture designed for PHP 8.4+ and PHP 8.5:
1. **Strongly-Typed Exceptions (Default):** Throws granular, domain-specific exceptions (`ChatNotFoundException`, `BotBlockedException`, `RateLimitException`, `CantParseEntitiesException`, etc.).
2. **Error-Object Mode:** Returns a typed `Tueen\Telegram\Types\Error` value object instead of throwing, with `TelegramErrorCode` enum reasons and helper checks.
3. **Automated Error Catalog:** Built from comprehensive Telegram Bot API error specifications with forward-compatibility fallback for unknown errors.

---

## 1. Universal `ok()` / `isOk()` Check

Every response object produced by the library inherits from [`Type`](./methods-and-types) and provides `ok(): bool` and `isOk(): bool`:

- **Successful Types (`Message`, `User`, `BooleanResult`, etc.):** Always return `true`.
- **`Error` Objects:** Always return `false`.

```php
$res = $bot->sendMessage(chatId: 12345, text: 'Hello!');

if ($res->ok()) {
    echo "Success! Message ID: {$res->messageId}\n";
} else {
    // $res is an Error object
    echo "Failed [{$res->errorCode}]: {$res->description}\n";
}
```

---

## 2. Strongly-Typed Exceptions Hierarchy

In standard exception mode, failed API requests throw specific exceptions matching the exact error returned by Telegram:

```
TelegramException
 └── ApiException
      ├── BadRequestException (400)
      │    ├── ChatNotFoundException
      │    ├── UserNotFoundException
      │    ├── MessageNotModifiedException
      │    ├── MessageNotFoundException
      │    ├── MessageCantBeDeletedException
      │    ├── MessageTooLongException
      │    ├── CantParseEntitiesException
      │    ├── CommandsListEmptyException
      │    └── ...
      ├── UnauthorizedException (401)
      ├── ForbiddenException (403)
      │    ├── BotBlockedException
      │    ├── BotKickedException
      │    ├── UserDeactivatedException
      │    └── NotEnoughRightsException
      ├── NotFoundException (404)
      ├── ConflictException (409)
      │    ├── WebhookActiveConflictException
      │    └── TerminatedByOtherGetUpdatesException
      ├── FileTooLargeException (413)
      ├── RateLimitException (429)
      └── NetworkException (cURL/connection failures)
```

### Catching Specific Exceptions

Catch the exact error condition directly without parsing strings:

```php
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\CantParseEntitiesException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\BadRequestException;
use Tueen\Telegram\Exceptions\ApiException;

try {
    $bot->sendMessage(chatId: $userId, text: $markdownText, parseMode: 'MarkdownV2');
} catch (BotBlockedException $e) {
    // User blocked the bot - mark user inactive in DB
    $user->update(['is_active' => false]);
} catch (ChatNotFoundException $e) {
    // Chat or channel no longer exists
    $logger->warning("Chat {$userId} not found.");
} catch (CantParseEntitiesException $e) {
    // Markdown syntax error - fallback to plain text
    $bot->sendMessage(chatId: $userId, text: strip_tags($markdownText));
} catch (RateLimitException $e) {
    // Flood wait: inspect retryAfter duration
    sleep($e->getRetryAfter());
} catch (BadRequestException $e) {
    // Catch-all for any other 400 Bad Request
} catch (ApiException $e) {
    // Catch-all for any other Telegram Bot API error
}
```

---

## 3. Typed Error Reasons (`TelegramErrorCode` Enum)

All API errors are mapped to the `TelegramErrorCode` Backed Enum:

```php
use Tueen\Telegram\Enums\TelegramErrorCode;

// Match cleanly using PHP 8.4 match expression
match ($error->reason) {
    TelegramErrorCode::BotBlocked => $user->markBlocked(),
    TelegramErrorCode::ChatNotFound => $user->delete(),
    TelegramErrorCode::FloodWait => sleep($error->getRetryAfter()),
    TelegramErrorCode::CantParseEntities => $bot->sendMessage(chatId: $chatId, text: $plain),
    default => $logger->error($error->description),
};
```

---

## 4. Error-Object Mode with Smart Helpers

To prevent API errors from throwing exceptions:

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Enums\TelegramErrorCode;

$bot = new Telegram(
    Telegram::create('YOUR_BOT_TOKEN')
        ->withErrorObjectMode()
        ->build()
);

$res = $bot->sendMessage(chatId: 99999, text: 'Hi');

if (!$res->ok()) {
    // 1. Inspect typed reason
    if ($res->is(TelegramErrorCode::BotBlocked)) {
        echo "User has blocked the bot.\n";
    }

    // 2. Convenience helpers
    if ($res->isChatNotFound()) { ... }
    if ($res->isBotBlocked()) { ... }
    if ($res->isRateLimit()) {
        $seconds = $res->getRetryAfter();
    }

    // 3. Convert to exception on demand
    $exception = $res->toException(); // returns ChatNotFoundException, etc.
}
```

---

## 5. Catch All Errors (Including Network Errors)

By default, `withErrorObjectMode()` only converts Telegram `ApiException` responses into `Error` objects.

If you also want connection drops, cURL errors, and network timeouts converted into `Error` objects:

```php
$bot = new Telegram(
    Telegram::create('YOUR_BOT_TOKEN')
        ->withCatchAllErrors()
        ->build()
);

$res = $bot->getMe();

if (!$res->ok()) {
    // Network/cURL errors receive negative error codes (e.g. -28)
    echo "Error: {$res->description} (Code: {$res->errorCode})\n";
}
```

---

## 6. Declarative `#[ApiErrors]` & Method Metadata

Every method generated by `tueen/telegram` declares its documented errors using the `#[ApiErrors]` attribute:

```php
use Tueen\Telegram\Methods\SendMessage;

$method = new SendMessage(chatId: 12345, text: 'Hello');

// Inspect expected errors programmatically
$expectedErrors = $method->getExpectedErrors();
// [TelegramErrorCode::ChatNotFound, TelegramErrorCode::BotBlocked, TelegramErrorCode::MessageTooLong, ...]
```

In IDEs (PhpStorm, VSCode), all methods in `TelegramMethods` and individual method classes contain `@throws` annotations for IDE autocompletion and inspection.

---

## 7. Lifecycle Event Hooks

You can register callbacks on the `Telegram` facade to observe or intercept requests and responses:

```php
$bot = new Telegram('YOUR_BOT_TOKEN');

// 1. Before sending HTTP request
$bot->onBeforeRequest(function (Request $request, Config $config) {
    // Log outbound request or attach telemetry spans
});

// 2. Immediately after raw HTTP response is received
$bot->onAfterRequest(function (Response $response, Request $request) {
    // Track HTTP status code and latency
});

// 3. When an error or exception occurs
$bot->onError(function (Throwable $error, Request $request) {
    // Send alert to Sentry, Bugsnag, or log
});

// 4. When the final result (Type or Error) is created
$bot->onResponse(function (Type $result, Request $request) {
    // Inspect the resulting Type or Error instance
});
```

---

## 8. Incoming Update Error Handling (`catch` & `onUpdateError`)

When handling incoming updates (in controllers, routes, conversation flows, or update handlers), uncaught exceptions can disrupt update dispatching if not handled. `tueen/telegram` provides first-class, bulletproof update exception handling:

### Universal Catch

Catches any `\Throwable` thrown during the processing of an incoming update:

```php
$bot->catch(function (Throwable $e, Update $update, Telegram $bot) {
    // 1. Log or report to monitoring service
    error_log("[Update {$update->updateId} Failed]: " . $e->getMessage());

    // 2. Safely reply to the user using contextual chatId
    $bot->reply('⚠️ An unexpected error occurred while processing your request.');
});
```

> [!TIP]
> You can also use `$bot->onUpdateError(...)` or `$app->catch(...)` as an identical fluent alias.

### Typed Catch (Specific Exception Filtering)

You can register granular error handlers for specific exception classes. Any unmatched exceptions will cascade to subsequent handlers or default logging:

```php
// Handles database query failures
$bot->catch(DatabaseException::class, function (DatabaseException $e, Update $update, Telegram $bot) {
    $bot->reply('Database temporarily unavailable. Please try again shortly.');
});

// Handles payment or order domain exceptions
$bot->catch(OrderExpiredException::class, function (OrderExpiredException $e, Update $update, Telegram $bot) {
    $bot->reply('Your order has expired. Please restart the checkout.');
});

// Fallback universal catch
$bot->catch(function (Throwable $e, Update $update, Telegram $bot) {
    $bot->reply('Something went wrong.');
});
```

### Quick Reply Helper (`$bot->reply`)

Inside any update handler or error handler, `$bot->reply($text, ...)` automatically resolves the active `chat_id` from the contextual update:

```php
// Automatically sends to current chat without manually extracting chatId
$bot->reply('Hello from Tueen!');

// Accepts formatted Text, keyboards, and extra API parameters
$bot->reply(
    text: Text::bold('Notice: ') . 'Service updated.',
    replyMarkup: InlineKeyboard::make()->url('Support', 'https://example.com/support')
);
```
