# Tueen Telegram 👑

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.5-8892BF.svg?style=flat-square)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)
[![Telegram Bot API](https://img.shields.io/badge/Telegram%20Bot%20API-10.3-2CA5E0.svg?style=flat-square&logo=telegram)](https://core.telegram.org/bots/api)

> **The Royal Telegram Bot API Client for Tueen**  
> *"Part of the Tueen ecosystem — The Queen of Telegram."*

`tueen/telegram` is a high-performance, strictly-typed Telegram Bot API client designed with a modern architecture. It provides complete coverage of the Telegram Bot API specification with zero legacy overhead, first-class IDE autocompletion, and forward compatibility.

---

## ⚡ Highlights

- 👑 **Complete Coverage:** All 185 Bot API methods and 400+ Types with 100% strict typing.
- 💎 **Modern Architecture:** Property hooks, asymmetric visibility (`private(set)`), pipe operator support, and typed enums.
- 🛡️ **Forward Compatibility:** Dynamic fallback ensures that unknown future fields or types never crash your application.
- 🎯 **Dual Access Styles:** Access properties via camelCase or snake_case ArrayAccess.
- 📊 **Real-Time Progress:** Stream upload and download progress callbacks out of the box.
- 🔌 **Extensible Pipeline:** Built-in automatic retries, flood-wait handling, PSR-3 logging, and custom middleware.
- 🌐 **PSR Standards:** Compliant with PSR-7, PSR-17, PSR-18, and PSR-3.

---

## 📦 Requirements & Installation

- **PHP 8.5+**
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

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
