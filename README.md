<p align="center">
  <a href="https://github.com/tueen/telegram">
    <img src="art/logo.png" alt="Tueen Telegram Logo" width="260">
  </a>
</p>

<h1 align="center">Tueen Telegram 👑</h1>

<p align="center">
  <strong>The Royal Telegram Bot API Client</strong><br>
  <em>Part of the Tueen ecosystem — "The Queen of Telegram"</em>
</p>

<p align="center">
  <a href="https://php.net"><img src="https://img.shields.io/badge/php-%3E%3D%208.5-8892BF.svg?style=flat-square" alt="PHP Version"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square" alt="License: MIT"></a>
  <a href="https://core.telegram.org/bots/api"><img src="https://img.shields.io/badge/Telegram%20Bot%20API-10.3-2CA5E0.svg?style=flat-square&logo=telegram" alt="Telegram Bot API"></a>
</p>

---

`tueen/telegram` is a high-performance, strictly-typed Telegram Bot API client designed with a modern architecture. It provides complete coverage of the Telegram Bot API specification with zero legacy overhead, first-class IDE autocompletion, and forward compatibility.

---

## ⚡ Highlights

- 👑 **Complete Coverage:** All 185 Bot API methods and 400+ Types with 100% strict typing.
- 💎 **Modern PHP Architecture:** Property hooks, asymmetric visibility (`private(set)`), pipe operator support, and typed enums.
- 🧭 **Attribute Routing:** Declarative update routing with `#[OnCommand]`, `#[OnCallbackQuery]`, regex matchers, and controllers.
- 💬 **Conversation Flows:** Stateful multi-step user dialogues (`to()`, `stay()`, `back()`, `finish()`) with pluggable state stores.
- ⌨️ **Fluent Keyboards & Formatting:** Intuitive Inline/Reply keyboard builders and Telegram-compliant HTML/MarkdownV2 builders.
- 🔄 **Flexible Running Modes:** Seamlessly switch between `WebhookMode` (with secret token validation and `safeResponse`) and `PollingMode`.
- 🛡️ **Dual Error Modes & ok():** Universal `ok()` checks across all models. Choose between standard exceptions or typed `Error` objects.
- 🎯 **Smart Helpers & Context:** Dynamic fallback, camelCase/ArrayAccess property access, and auto-resolved chat/user context IDs.
- 🔌 **Resilient Pipeline:** Extensible onion middleware featuring automatic 429 flood-wait pacing, retries, and PSR-3 logging.
- 🧪 **In-Memory Testing:** Test bots with zero HTTP calls using `TelegramFake`, stub responses, and expressive assertions (`assertSent`).

---

## 📦 Requirements & Installation

- **PHP 8.4+** (with full PHP 8.5+ support)
- Composer 2.x

```bash
composer require tueen/telegram
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
