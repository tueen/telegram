# Tueen Telegram 👑

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.4%20%7C%208.5-8892BF.svg?style=flat-square)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)
[![Telegram Bot API](https://img.shields.io/badge/Telegram%20Bot%20API-10.3-2CA5E0.svg?style=flat-square&logo=telegram)](https://core.telegram.org/bots/api)

> **The Royal Telegram Bot API Client for Tueen**  
> *"Part of the Tueen ecosystem — The Queen of Telegram."*

`tueen/telegram` is a high-performance, elegant, and ultra-modern Telegram Bot API client crafted specifically for **PHP 8.4** and **PHP 8.5**. It leverages the cutting-edge features of modern PHP—such as **Property Hooks**, **Asymmetric Visibility**, **Attributes**, and **Pipeline/Middleware architecture**—with 100% complete coverage of the Telegram Bot API 10.3.

---

## ⚡ Features

- 👑 **Complete Coverage:** All 185 Bot API methods and 400+ Types with strict typing and docblocks.
- 🚀 **PHP 8.4 & 8.5 Power:** Property hooks, asymmetric visibility (`public private(set)`), pipe operator compatibility (`|>`), and modern typed enums.
- 🛡️ **Bulletproof Forward Compatibility:** Dynamic fallback ensures that unknown future Telegram fields or types never crash your application.
- 🎯 **Dual Access Styles:** Access fields using camelCase properties (`$message->messageId`) or snake_case ArrayAccess (`$message['message_id']`).
- 📊 **Real-Time Progress Tracking:** Track upload and download percentages in real-time (`fn($bytes, $total, $pct)`).
- 🔌 **Extensible Pipeline:** Built-in automatic retries (flood wait / 429), PSR-3 logging, and custom middleware support.
- 🌐 **PSR Compliant:** PSR-7, PSR-17, PSR-18, and PSR-3 compliant.

---

## 📦 Installation

```bash
composer require tueen/telegram
```

---

## 🚀 Quick Start

### Basic Usage

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Enums\ParseMode;

// Initialize the Royal Client
$telegram = new Telegram('YOUR_BOT_TOKEN');

// 1. Dynamic Method Call with Named Arguments
$message = $telegram->sendMessage(
    chatId: 123456789,
    text: 'Hello from <b>Tueen</b>! 👑',
    parseMode: ParseMode::HTML->value
);

echo "Sent message ID: {$message->messageId}\n";
echo "Sent to chat: {$message['chat']['id']}\n";
```

### Fluent Configuration Builder

```php
use Tueen\Telegram\Telegram;

$telegram = Telegram::create('YOUR_BOT_TOKEN')
    ->withTimeout(30.0)
    ->withProxy('http://127.0.0.1:10809')
    ->withRetryCount(3)
    ->withUploadProgress(function (int $bytes, int $total, float $pct) {
        echo "Uploading: {$pct}%\r";
    })
    ->build();
```

### File Upload with Real-Time Progress

```php
use Tueen\Telegram\Methods\SendPhoto;
use Tueen\Telegram\Types\Custom\InputFile;

$photo = InputFile::fromPath('/path/to/royal_queen.jpg');

$telegram->send(
    new SendPhoto(
        chatId: 123456789,
        photo: $photo,
        caption: 'The Royal Queen 👑'
    ),
    uploadProgress: function (int $uploaded, int $total, float $pct) {
        echo "Upload progress: {$pct}%\n";
    }
);
```

### File Download with Progress

```php
$telegram->downloadFile(
    file: $photoFileId,
    destination: '/path/to/save/downloaded.jpg',
    progress: function (int $downloaded, int $total, float $pct) {
        echo "Downloaded: {$pct}%\n";
    }
);
```

### Webhook Handling

```php
// In your webhook controller:
$update = $telegram->handleWebhook();

if ($update->message?->text) {
    $telegram->sendMessage(
        chatId: $update->message->chat->id,
        text: "You said: {$update->message->text}"
    );
}
```

---

## 📖 Documentation

Full interactive documentation powered by VitePress is available in the [`docs/`](./docs) directory.

To run the documentation locally:

```bash
cd docs
npm install
npm run docs:dev
```

---

## 🧪 Testing

```bash
vendor/bin/phpunit
```

---

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
