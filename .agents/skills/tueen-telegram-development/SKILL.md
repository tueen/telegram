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

---

## 🛠️ Verification Commands

Before committing any changes:

1. **Lint all 600+ classes:**
   ```powershell
   php scratch/validate_all.php
   ```

2. **Run PHPUnit test suite:**
   ```powershell
   vendor/bin/phpunit
   ```
