<p align="center" style="margin-top: 2rem; margin-bottom: 2.5rem;">
  <img src="/logo.png" alt="Tueen Telegram Logo" width="300" style="max-width: 100%; height: auto; filter: drop-shadow(0 12px 30px rgba(0, 0, 0, 0.15));" />
</p>

# Getting Started

## Introduction

> *The Royal Telegram Bot SDK for Modern PHP*

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

$bot = new Telegram('YOUR_BOT_TOKEN');
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

$bot = new Telegram($config);
```

::: tip Building a Standalone Bot with a Web Dashboard?
If you're building a standalone bot from scratch, `tueen/telegram` also includes **`App`** — a zero-config application orchestrator featuring a 3-file project template, automated session storage, CLI tools, and a real-time **Web Setup & Management Dashboard**. See the [Zero-Config App & Web Dashboard Guide](./app).
:::

---

## Supported Bot API Version

You can inspect the supported Telegram Bot API version programmatically at runtime:

```php
use Tueen\Telegram\Telegram;

echo Telegram::BOT_API_VERSION;
```

---

## First API Call: `getMe`

```php
$me = $bot->getMe();

if ($me->ok()) {
    echo "Bot ID: {$me->id}\n";
    echo "Bot Username: @{$me->username}\n";
}
```

---

## Sending a Message

You can call any Telegram Bot API method directly using named arguments:

```php
use Tueen\Telegram\Enums\ParseMode;

$message = $bot->sendMessage(
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
$bot->run(function (Update $update, Telegram $bot) {
    $message = $update->findMessage();
    if ($message !== null) {
        $bot->sendMessage(
            chatId: $message->chat->id,
            text: "Received: {$message->findAnyText()}"
        );
    }
});
```

---

## Next Steps

Now that your bot is running, explore the core essentials:

- 💬 **[Calling Methods & Types](./methods-and-types):** Learn how to invoke Bot API methods with named arguments and contextual auto-injection.
- ⌨️ **[Interactive Keyboards](./keyboards):** Build Inline and Reply keyboards with fluent builders.
- 📝 **[Text Formatting & Escaping](./formatting):** Format messages safely using HTML and MarkdownV2 without entity parsing errors.
- 🧭 **[Update Routing & Attributes](./routing):** Organize commands, regex patterns, and callback queries with attributes and controllers.
- 🔄 **[Running Modes](./running-modes):** Dive deeper into Webhook, Polling, and AutoMode configurations.
