# Getting Started

## Introduction

`tueen/telegram` is the royal Telegram Bot API client for PHP 8.4 and PHP 8.5. It is designed to be lightweight, ultrafast, strictly typed, and future-proof.

## Requirements

- **PHP 8.4+** or **PHP 8.5+**
- **Composer 2.x**
- Extensions: `json`, `curl` or stream wrappers

## Installation

```bash
composer require tueen/telegram
```

## Basic Setup

You can initialize the client either directly with your bot token:

```php
use Tueen\Telegram\Telegram;

$telegram = new Telegram('YOUR_BOT_TOKEN');
```

Or using the fluent `ConfigBuilder`:

```php
use Tueen\Telegram\Telegram;

$telegram = Telegram::create('YOUR_BOT_TOKEN')
    ->withTimeout(30.0)
    ->withProxy('http://127.0.0.1:10809')
    ->withRetryCount(3)
    ->build();
```

## First Call: getMe

```php
$bot = $telegram->getMe();

echo "Bot ID: " . $bot->id . PHP_EOL;
echo "Bot Username: @" . $bot->username . PHP_EOL;
```
