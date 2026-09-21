# Getting Started

## Introduction

`tueen/telegram` is a modern Telegram Bot API client for PHP. It provides strict typing, full IDE autocompletion, and forward compatibility with the Telegram Bot API specification.

---

## Requirements

- **PHP 8.4+** or **PHP 8.5+**
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

---

## Handling Incoming Updates

Choose between **Webhook Mode** (recommended for production) and **Polling Mode** (ideal for CLI/local testing):

```php
use Tueen\Telegram\Types\Update;

// Run the bot using the configured running mode
$telegram->run(function (Update $update) use ($telegram) {
    $message = $update->findMessage();
    if ($message !== null) {
        $telegram->sendMessage(
            chatId: $message->chat->id,
            text: "Received: {$message->findAnyText()}"
        );
    }
});
```

Learn more about execution strategies in the [Running Modes Guide](./running-modes).
