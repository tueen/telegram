<p align="center">
  <a href="https://github.com/tueen/telegram">
    <img src="art/logo.png" alt="Tueen Telegram Logo" width="260">
  </a>
</p>

<h1 align="center">Tueen Telegram 👑</h1>

<p align="center">
  <strong>The Royal Telegram Bot SDK for Modern PHP</strong><br>
  <em>Part of the Tueen ecosystem — "The Queen of Telegram"</em>
</p>

<p align="center">
  <a href="https://php.net"><img src="https://img.shields.io/badge/php-%3E%3D%208.4-8892BF.svg?style=flat-square" alt="PHP Version"></a>
  <a href="https://github.com/tueen/telegram/actions/workflows/ci.yml"><img src="https://img.shields.io/github/actions/workflow/status/tueen/telegram/ci.yml?branch=main&label=CI&style=flat-square" alt="Continuous Integration"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square" alt="License: MIT"></a>
  <a href="https://core.telegram.org/bots/api"><img src="https://img.shields.io/badge/Telegram%20Bot%20API-10.3-2CA5E0.svg?style=flat-square&logo=telegram" alt="Telegram Bot API"></a>
</p>

---

`tueen/telegram` is a high-performance, strictly-typed Telegram Bot SDK crafted for modern PHP (8.4+). Built with zero legacy overhead, it pairs complete Bot API coverage with declarative attribute routing, stateful conversation flows, an embedded real-time web dashboard, reusable connection pooling, and royal developer ergonomics.

---

## ⚡ Highlights

- 🚀 **Zero-Config App & Web Dashboard:** Instant boot with `App::run()`, CLI toolkit, and a live web UI for webhook inspection and bot health.
- 👑 **Complete Coverage:** All Telegram Bot API 10.3 methods and types with 100% strict typing.
- 💎 **Modern PHP Architecture:** Property hooks, asymmetric visibility (`private(set)`), pipe operator support, and typed enums.
- 🧭 **Expressive Routing & Groups:** Declarative routing with `#[OnCommand]`, `group()`, route-level middlewares, parameter regex constraints (`where`), and automatic dependency injection.
- 💬 **Conversation Flows:** Stateful multi-step user dialogues (`to()`, `stay()`, `back()`, `finish()`) with pluggable state stores.
- 🔄 **Adaptive Running Modes:** Seamlessly switch between `WebhookMode` (with secret token & `safeResponse`), `PollingMode`, and `AutoMode`.
- ⌨️ **Fluent Keyboards & Formatting:** Intuitive Inline/Reply keyboard builders with direct JSON serialization and Telegram-compliant HTML/MarkdownV2 builders.
- 🔌 **Resilient Pipeline & Connection Pooling:** Persistent TCP/TLS cURL handle pooling, token-bucket 429 rate limiting, automatic retries, and PSR-3 logging.
- 🎯 **Thread-Safe Scoped Execution:** Isolate concurrent requests in persistent worker engines (`FrankenPHP`, `RoadRunner`, `Swoole`, `Laravel Octane`) via `$bot->scoped($update)`.
- 🛡️ **Dual Error Modes & ok():** Universal `ok()` checks across all models. Choose between standard exceptions or typed `Error` objects.
- 🧪 **In-Memory Testing:** Test bots with zero HTTP calls using `TelegramFake`, stub responses, and expressive assertions (`assertSent`, `assertReplyText`).

---

## 📦 Requirements & Installation

- **PHP 8.4+** (with full PHP 8.5+ support)
- Composer 2.x

```bash
composer require tueen/telegram
```

---

## 🚀 Quickstart

### 1. Simple Polling Bot

```php
<?php

use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\User;

require __DIR__ . '/vendor/autoload.php';

$bot = new Telegram('YOUR_BOT_TOKEN');

// Auto-wired parameters: User, Chat, Message, and Telegram are injected automatically
$bot->onCommand('start', function (User $user, Telegram $bot) {
    $bot->reply("Welcome to Tueen, {$user->firstName}! 👑");
});

// Route groups with middlewares and parameter constraints
$bot->group(['prefix' => 'order_'], function (Telegram $bot) {
    $bot->onText('{id}', function (int $id, Telegram $bot) {
        $bot->reply("Order #{$id} confirmed!");
    })->where('id', '[0-9]+');
});

// Run continuous long-polling
$bot->run();
```

### 2. Zero-Config App with Web Dashboard & Webhooks

```php
<?php

use Tueen\Telegram\App;

require __DIR__ . '/vendor/autoload.php';

// Automatically selects Webhook in HTTP and Polling in CLI
App::run(__DIR__);
```

---

## 📖 Documentation

All detailed guides, real-world examples, configuration options, and running modes are available in our official documentation:

👉 **[Explore Full Documentation](./docs)**

To run the interactive docs locally:

```bash
cd docs
npm install
npm run docs:dev
```

---

## 📜 License

Licensed under the [MIT License](LICENSE.md).
