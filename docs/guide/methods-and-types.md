# Methods & Types

## Dynamic and Typed Method Calling

There are two primary ways to call methods in `tueen/telegram`:

### 1. Dynamic Method Calls (Recommended for Simplicity)

You can call any Telegram method directly using camelCase and named arguments:

```php
use Tueen\Telegram\Enums\ParseMode;

$message = $telegram->sendMessage(
    chatId: 12345678,
    text: "Hello from <b>Tueen</b>!",
    parseMode: ParseMode::HTML->value
);
```

### 2. Method Objects (Recommended for Complex Payloads & Architecture)

Every Telegram Bot API method has a dedicated class in `Tueen\Telegram\Methods`:

```php
use Tueen\Telegram\Methods\SendMessage;

$method = new SendMessage(
    chatId: 12345678,
    text: "Hello from Tueen!"
);

$message = $telegram->send($method);
```

## Types & Property Access

All API responses are automatically deserialized into strongly-typed objects in `Tueen\Telegram\Types`.

### Dual Access: camelCase and snake_case

```php
// CamelCase property access (PHP 8.4 asymmetric visibility)
echo $message->messageId;
echo $message->chat->firstName;

// Snake_case property access
echo $message->message_id;

// ArrayAccess with snake_case
echo $message['message_id'];
echo $message['chat']['first_name'];
```

## Forward Compatibility & Dynamic Fallback

If Telegram adds a new field tomorrow that isn't yet part of the library, your application will **never crash**:

- The unknown field is preserved in `$extra` storage.
- You can access it immediately via `$message->newField` or `$message['new_field']`.
- If Telegram returns an entirely new Type object, it seamlessly falls back to the base `Type` class.
