# AGENTS.md - Developer & AI Guide for `tueen/telegram`

Welcome to `tueen/telegram` — **The Royal Telegram Bot API Client for PHP 8.4 & 8.5**, a proud cornerstone of the **Tueen Ecosystem** (*"The Queen of Telegram"*).

---

## 👑 Library Identity & Mission

- **Package Name:** `tueen/telegram`
- **Slogan:** *The Royal Telegram Bot API Client for Tueen*
- **Target Runtime:** PHP 8.4+ / PHP 8.5+ (100% strict types, property hooks, asymmetric visibility, attributes, modern pipeline pattern).
- **Coverage:** Complete Telegram Bot API 10.3 (all 185 methods, 400 types, and property enums).

---

## 🏛️ Architecture & Directory Structure

```
tueen/telegram/
├── src/
│   ├── Telegram.php                   # Royal Facade and main client entry point (@mixin TelegramMethods)
│   ├── Contracts/
│   │   └── TelegramMethods.php        # Method signatures mixin (all 185 Bot API methods for IDE)
│   ├── Config.php                     # Immutable client configuration
│   ├── ConfigBuilder.php              # Fluent configuration builder
│   ├── Types/                         # All Telegram Bot API types (400 types)
│   │   ├── Type.php                   # Base Type with dynamic fallback & ArrayAccess
│   │   └── Custom/InputFile.php       # Multipart file wrapper
│   ├── Methods/                       # All Telegram Bot API methods (185 methods)
│   │   └── Method.php                 # Base Method class with multipart & serialization
│   ├── Enums/                         # Standard Backed Enums (ParseMode, ChatType, etc.)
│   ├── Properties/                    # Backward-compatibility alias layer for Enums
│   ├── Client/                        # HTTP client abstraction (PSR-18 / Guzzle 7)
│   │   ├── HttpClientInterface.php
│   │   ├── GuzzleHttpClient.php
│   │   ├── Request.php
│   │   └── Response.php
│   ├── Pipeline/                      # Middleware pipeline for request/response interception
│   │   ├── Pipeline.php
│   │   ├── MiddlewareInterface.php
│   │   ├── RetryMiddleware.php
│   │   └── LoggingMiddleware.php
│   ├── Attributes/                    # PHP 8 attributes (ApiMethod, ReturnType, Field, ArrayOf)
│   ├── Exceptions/                    # Typed exception tree
│   └── Generator/                     # Code generator from Telegram Bot API schema
├── docs/                              # VitePress documentation
├── tests/                             # PHPUnit test suite
├── composer.json
└── README.md
```

---

## 💎 Key Development Conventions

### 1. PHP 8.4 & 8.5 Features
- **Asymmetric Visibility:** Used across response Type objects: `public private(set) int $id;`.
- **Property Hooks:** Used for computed, validated, and normalized properties.
- **Dynamic Forward-Compatibility:** 
  - Unknown fields returned by future Telegram updates are automatically saved in `$extra` and accessible via `$type->fieldName`, `$type->field_name`, and `$type['field_name']`.
  - Unknown objects fallback to the base `Type` class, preventing runtime deserialization crashes.
- **PHP 8.5 Pipe Operator:** Middleware pipeline and data transforms are compatible with PHP 8.5 `|>` and `$telegram->pipe()`.

### 2. File Uploads & Progress
- Always use `Tueen\Telegram\Types\Custom\InputFile` for uploading files (`fromPath`, `fromResource`, `fromString`, `fromStream`).
- Progress callbacks accept `(int $bytesUploaded, int $totalBytes, float $percentage)`.

### 3. Code Generation
- When updating API specifications: run `php bin/generate.php` to regenerate all types and methods from `scratch/api.json` or latest schema.

### 4. Testing
- Run test suite: `vendor/bin/phpunit`
- Lint code: `php scratch/validate_all.php`
