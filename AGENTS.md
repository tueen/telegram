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
│   ├── Running/                       # Running modes (Nutgram-style execution strategies)
│   │   ├── RunningModeInterface.php
│   │   ├── WebhookMode.php            # Webhook runner (secret_token validation, safeResponse)
│   │   └── PollingMode.php            # Long-polling runner (offset tracking, auto-backoff)
│   ├── Types/                         # All Telegram Bot API types (400 types)
│   │   ├── Type.php                   # Base Type with universal ok() check, dynamic fallback & ArrayAccess
│   │   ├── Error.php                  # Typed error object for non-throwing error handling
│   │   ├── Concerns/                  # Type helper traits with PHP 8.4 property hooks
│   │   │   ├── HasUpdateHelpers.php   # $update->type, isType, getMessage, getUser, getChat
│   │   │   └── HasMessageHelpers.php  # $message->type, isType, isCommand, getCommand, getArgs
│   │   └── Custom/InputFile.php       # Multipart file wrapper
│   ├── Methods/                       # All Telegram Bot API methods (185 methods)
│   │   └── Method.php                 # Base Method class with multipart & serialization
│   ├── Enums/                         # Standard Backed Enums (ParseMode, ChatType, UpdateType, MessageType, ErrorHandlingMode, etc.)
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
- **Property Hooks:** Used for computed, validated, and normalized properties (e.g. `$update->type`, `$message->type`).
- **Dynamic Forward-Compatibility:** 
  - Unknown fields returned by future Telegram updates are automatically saved in `$extra` and accessible via `$type->fieldName`, `$type->field_name`, and `$type['field_name']`.
  - Unknown objects fallback to the base `Type` class, preventing runtime deserialization crashes.
- **PHP 8.5 Pipe Operator:** Middleware pipeline and data transforms are compatible with PHP 8.5 `|>` and `$telegram->pipe()`.

### 2. Universal `ok()` & Dual Error Handling Modes
- All API types inherit from `Type` and provide `ok(): bool` and `isOk(): bool` (returning `true`).
- `ErrorHandlingMode::EXCEPTION` (default) throws typed exceptions (`ApiException`, `RateLimitException`, etc.).
- `ErrorHandlingMode::ERROR_OBJECT` returns `Tueen\Telegram\Types\Error` instances (where `ok()` returns `false`), allowing non-throwing code styles.
- Internal/network errors caught via `withCatchAllErrors()` receive negative error codes (e.g. `-28`) to easily distinguish them from Telegram API HTTP errors.

### 3. Running Modes & Smart Helpers (Nutgram-Style)
- Support both `WebhookMode` and `PollingMode` via `RunningModeInterface`.
- `WebhookMode`: Supports automatic secret token verification (`X-Telegram-Bot-Api-Secret-Token`), raw payload parsing, and immediate response flushing via `safeResponse()`.
- `PollingMode`: Automatically handles `offset` advancement (`update_id + 1`), transient error backoff, and manual generator iteration.
- Client provides `$telegram->getUpdate()`, `$telegram->run(?callable $handler)`, and `$telegram->poll()`.
- Smart in-memory finders on `Update`: `$update->findMessage()`, `$update->findUser()`, `$update->findChat()`.
- Content & command helpers on `Message`: `$message->findAnyText()`, `$message->isCommand()`, `$message->getCommand()`, `$message->getArgs()`.

### 4. Lifecycle Event Hooks
- Fluent hooks on `Telegram`: `onBeforeRequest`, `onAfterRequest`, `onError`, `onResponse`.

### 5. File Uploads & Progress
- Always use `Tueen\Telegram\Types\Custom\InputFile` for uploading files (`fromPath`, `fromResource`, `fromString`, `fromStream`).
- Progress callbacks accept `(int $bytesUploaded, int $totalBytes, float $percentage)`.

### 6. Documentation & Knowledge Synchronization (Mandatory Rule)
- Whenever any feature, class, enum, or method is added or modified:
  - Keep `docs/` completely updated with full documentation, guides, and real-world examples.
  - Update `AGENTS.md` and `.agents/skills/` with any architectural changes. Only modify or add what is strictly necessary.
- **Documentation Versioning:**
  - `docs/versions.json` defines the active `current` version (e.g. `1.0.0-alpha.1`) and `archived` historical versions.
  - To archive the current documentation: `cd docs && npm run docs:archive <version> [next-version]`.
  - To change current version tag: `cd docs && npm run docs:version <version>`.
  - Archived versions snapshot their sidebar in `docs/versions/<version>/sidebar.json` and are served with zero configuration under `/versions/<version>/`.

### 7. Testing & Verification
- Run test suite: `vendor/bin/phpunit`
- Lint code: `composer lint` (or `php scratch/validate_all.php`)
