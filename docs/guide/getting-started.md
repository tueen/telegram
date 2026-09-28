# Getting Started

## Introduction

`tueen/telegram` is a modern Telegram Bot API client for PHP. It provides strict typing, full IDE autocompletion, and forward compatibility with the Telegram Bot API specification.

---

## Requirements

- **PHP 8.4+**
- **Composer 2.x**
- Extensions: `json`, `curl` or stream wrappers

---

## Installation

Install the package via Composer:

```bash
composer require tueen/telegram
```

---

## Client Initialization

### Direct Initialization
If you just need standard defaults:

```php
use Tueen\Telegram\Telegram;

$telegram = new Telegram('YOUR_BOT_TOKEN');
```

### Fluent ConfigBuilder
For advanced options (proxies, timeouts, error modes, test environment):

```php
use Tueen\Telegram\Telegram;

$config = Telegram::create('YOUR_BOT_TOKEN')
    ->withTimeout(30.0)
    ->withProxy('http://127.0.0.1:10809')
    ->withRetryCount(3)
    ->withErrorObjectMode()
    ->build();

$telegram = new Telegram($config);
```

---

## Supported Bot API Version

You can inspect the supported Telegram Bot API version programmatically at runtime:

```php
use Tueen\Telegram\Telegram;

echo Telegram::BOT_API_VERSION; // "10.3"
```

---

## First API Call: `getMe`

```php
$bot = $telegram->getMe();

if ($bot->ok()) {
    echo "Bot ID: {$bot->id}\n";
    echo "Bot Username: @{$bot->username}\n";
}
```

---

## Sending a Message

You can call any Telegram Bot API method directly using named arguments:

```php
use Tueen\Telegram\Enums\ParseMode;

$message = $telegram->sendMessage(
    chatId: 123456789,
    text: 'Hello from <b>tueen/telegram</b>!',
    parseMode: ParseMode::HTML
);

echo "Message sent with ID: {$message->messageId}\n";
```

::: tip Always Use Named Parameters
In `tueen/telegram`, **always invoke API methods using PHP named parameters** (e.g. `chatId: ...`, `text: ...`, `parseMode: ...`). Named parameters make your code self-documenting, protect against argument reordering in future Bot API updates, and allow you to omit contextual parameters (like `chatId`) seamlessly.
:::

---

## Handling Incoming Updates

Choose between **Webhook Mode** (recommended for production) and **Polling Mode** (ideal for CLI/local testing):

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

// Run the bot using the configured running mode (Webhook or Polling)
$telegram->run(function (Update $update, Telegram $bot) {
    $message = $update->findMessage();
    if ($message !== null) {
        $bot->sendMessage(
            chatId: $message->chat->id,
            text: "Received: {$message->findAnyText()}"
        );
    }
});
```

Learn more about execution strategies, invokable handler classes, and multi-process polling in the [Running Modes Guide](./running-modes).
