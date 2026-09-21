---
name: tueen-telegram-development
description: >-
  Use this skill when developing, testing, or extending features in the tueen/telegram library,
  including creating middlewares, modifying client transport, handling file uploads/downloads with progress,
  and enforcing PHP 8.4/8.5 coding standards.
---

# Tueen Telegram Development & Contribution Guide

This skill provides step-by-step procedures and rules for developing, testing, and extending `tueen/telegram`.

---

## 💎 Core Architecture Rules

### 1. Modern PHP 8.4 & 8.5 Standards
- Always enforce `declare(strict_types=1);`.
- Use **Asymmetric Visibility** (`public private(set)`) on all response Types.
- Use **Property Hooks** for dynamic transformation, normalization, or validation.
- Respect the PHP 8.5 Pipe Operator (`|>`) and fluent middleware pipeline (`$telegram->pipe(...)`).

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
- Provide `$telegram->getUpdate()`, `$telegram->run(?callable $handler)`, and `$telegram->poll()`.
- Ensure `WebhookMode` handles `secret_token` validation and `safeResponse()` without blocking the server.
- Ensure `PollingMode` maintains correct update offsets (`update_id + 1`) and backoff logic.

### 5. Dual Error Handling & Universal `ok()` Guarantee
- Every API response extends `Type` and returns `true` from `ok()` and `isOk()`.
- On failure under `ErrorHandlingMode::ERROR_OBJECT`, return `Tueen\Telegram\Types\Error` where `ok()` returns `false`.
- Ensure internal/cURL/network exceptions converted to `Error` receive negative integer error codes.
- Support lifecycle event hooks: `onBeforeRequest`, `onAfterRequest`, `onError`, `onResponse`.

### 6. Update & Message Helpers
- Leverage PHP 8.4 property hooks for `$update->type` (`UpdateType`) and `$message->type` (`MessageType`).
- Provide smart extractors: `$update->getMessage()`, `$update->getUser()`, `$update->getChat()`.
- Provide command extractors: `$message->isCommand()`, `$message->getCommand()`, `$message->getArgs()`, `$message->getText()`.

### 7. Documentation & Knowledge Synchronization (Mandatory Rule)
- Whenever any feature, class, enum, or configuration option is added or modified:
  - Immediately update `docs/` (sidebar, guides, code snippets) with comprehensive explanations and examples.
  - Update `AGENTS.md` and this skill file to record any new architectural conventions or rules.
  - Only update what is relevant and necessary to keep docs clean and accurate.
- **Documentation Versioning:**
  - `docs/versions.json` specifies `current` version (e.g. `1.0.0-alpha.1`) and `archived` releases.
  - To archive a released version: `npm run docs:archive <version> [next-version]`.
  - To bump current version: `npm run docs:version <version>`.

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

