---
name: tueen-telegram-development
description: >-
  Use this skill when developing, testing, or extending features in the tueen/telegram library,
  including creating middlewares, modifying client transport, handling file uploads/downloads with progress,
  and enforcing modern PHP coding standards.
---

# Tueen Telegram Development & Contribution Guide

This skill provides step-by-step procedures and rules for developing, testing, and extending `tueen/telegram`.

---

## 💎 Core Architecture Rules

### 1. Modern PHP Coding Standards
- Always enforce `declare(strict_types=1);`.
- Refer to `php-84-standards` for Property Hooks, Asymmetric Visibility, and `array_*()` utilities.
- Refer to `php-85-standards` for Pipe Operator (`|>`), `clone with`, `#[\NoDiscard]`, and persistent share handles.
- Use **Asymmetric Visibility** (`private(set)`) on all response Types. Never write redundant `public private(set)` as read-visibility is public by default.
- Use **Property Hooks** for dynamic transformation, normalization, or validation.
- Support the Pipe Operator (`|>`) and fluent middleware pipeline (`$telegram->pipe(...)`).

### 2. Forward-Compatibility & Resilience
- Every response model must extend `Tueen\Telegram\Types\Type`.
- Never remove or bypass the dynamic `$extra` array and fallback logic in `Type.php`.
- Support both `camelCase` property access (`$msg->messageId`) and `snake_case` ArrayAccess (`$msg['message_id']`).

### 3. File Uploads & Progress Tracking
- Wrap all uploadable content in `Tueen\Telegram\Types\Custom\InputFile` (`fromPath`, `fromResource`, `fromString`, `fromStream`).
- Always support progress reporting callbacks:
  ```php
  function (int $bytesTransferred, int $totalBytes, float $percentage): void
  ```
- Use streaming sinks for downloads to avoid memory limits:
  ```php
  $telegram->downloadFile($fileId, $destination, progress: $progressCallback);
  ```

### 4. Running Modes & Handlers
- Support interchangeable execution strategies via `RunningModeInterface` (`WebhookMode`, `PollingMode`).
- Provide `$telegram->run(mixed ...$handlers)`, `$telegram->handle(...)`, and `$telegram->poll()`.
- Populate `$telegram->update` with the active `Update` instance (defaults to `null`).
- Ensure `WebhookMode` handles `secret_token` validation and `safeResponse()` without blocking the server.
- Ensure `PollingMode` maintains correct update offsets (`update_id + 1`), backoff logic, and configurable concurrency (`forkProcess`, `processDispatcher`).

### 5. Dual Error Handling & Universal `ok()` Guarantee
- Every API response extends `Type` and returns `true` from `ok()` and `isOk()`.
- On failure under `ErrorHandlingMode::ERROR_OBJECT`, return `Tueen\Telegram\Types\Error` where `ok()` returns `false`.
- Ensure internal/cURL/network exceptions converted to `Error` receive negative integer error codes.
- Support lifecycle event hooks: `onBeforeRequest`, `onAfterRequest`, `onError`, `onResponse`.

### 6. Update & Message Helpers
- Leverage PHP 8.4 property hooks for `$update->type` (`UpdateType`) and `$message->type` (`MessageType`).
- Provide smart in-memory finders: `$update->findMessage()`, `$update->findUser()`, `$update->findChat()`.
- Provide content & command extractors: `$message->findAnyText()`, `$message->isCommand`, `$message->getCommand()`, `$message->getArgs()`.

### 7. Fluent Keyboard Builders
- `InlineKeyboard::make()` provides a fluent interface for `InlineKeyboardMarkup` (callback, url, webApp, loginUrl, copyText, pay, switchInlineQuery, chunk).
- `ReplyKeyboard::make()` provides a fluent interface for `ReplyKeyboardMarkup` (text, requestContact, requestLocation, requestPoll, requestUsers, requestChat, webApp, resize, oneTime, persistent, placeholder, chunk) and `ReplyKeyboard::remove()`.

### 8. Formatting & Escaping Strictly Conforming to Telegram Spec
- `Escape`: Low-level escaping strictly conforming to [Telegram Bot API formatting options](https://core.telegram.org/bots/api#formatting-options) (`html`, `markdownV2`, `markdownV2Code`, `markdownV2Link`, `markdown`).
- `Text`: Fluent builder for constructing safely formatted HTML and MarkdownV2 messages (`bold`, `italic`, `underline`, `strikethrough`, `spoiler`, `blockquote`, `expandableBlockquote`, `code`, `pre`, `link`, `userMention`, `customEmoji`, `line`, `lines`, `plain`, `raw`).

### 9. Update Routing & Attribute Controllers
- `Router` & `Route`: Flexible update routing matching commands (auto-stripping `@bot`), parameterized callback queries (`order:{id}`), text patterns/regex, inline queries, and fallbacks.
- Declarative PHP 8 Attributes: `#[OnCommand]`, `#[OnCallbackQuery]`, `#[OnMessage]`, `#[OnInlineQuery]`, `#[OnUpdate]`.
- Controller registration via `$telegram->registerController(...)` with PSR-11 container dependency injection.

### 10. Testing Fakes & Assertions
- `Telegram::fake()` enables comprehensive unit testing without hitting real Telegram servers.
- Built-in assertions: `assertSent()`, `assertNotSent()`, `assertSentCount()`, `assertNothingSent()`.
- Response and error stubbing: `fakeResponse()`, `fakeError()`.

### 11. Rate Limiting Middleware
- `RateLimitMiddleware`: Token bucket pacing (30 req/sec globally), per-chat interval (1.0 sec), and automatic 429 `retry_after` backoff and re-execution.

### 12. Documentation & Knowledge Synchronization (Mandatory Rule)
- Whenever any feature, class, enum, or configuration option is added or modified:
  - Immediately update `docs/` (sidebar, guides, code snippets) with comprehensive explanations and examples.
  - Update `AGENTS.md` and this skill file to record any new architectural conventions or rules.
  - Only update what is relevant and necessary to keep docs clean and accurate.
- **Documentation Versioning:**
  - `docs/versions.json` specifies `current` version (e.g. `1.0.0-alpha.1`) and `archived` releases.
  - To archive a released version: `npm run docs:archive <version> [next-version]`.
  - To bump current version: `npm run docs:version <version>`.

### 8. Telegram Bot API Version Constant & Synchronization Rule
- The supported Telegram Bot API version is exposed via `Telegram::BOT_API_VERSION` (and `Telegram::API_VERSION`).
- When a new Telegram Bot API version is released and `resources/api.json` is updated, `Telegram::BOT_API_VERSION` must be updated (automatically handled by `bin/generate.php` and verified via `TelegramClientTest`).

---

## 🛠️ Verification Commands

Before committing any changes:

1. **Lint all 650+ classes:**
   ```powershell
   composer lint
   ```

2. **Run PHPUnit test suite:**
   ```powershell
   vendor/bin/phpunit
   ```

