# Tueen Telegram Rules & Standards

When creating, extending, or maintaining code in `tueen/telegram`:

---

## 1. Modern Strict Architecture
- Always enforce `declare(strict_types=1);`.
- **Asymmetric Visibility:** Always use `private(set)` on properties populated from API responses or internal state. Never write redundant `public private(set)` as read-visibility defaults to `public`.
- **Property Hooks:** Use native property hooks (`get`, `set`) for computed values, command checks, and normalization instead of getter/setter boilerplate.
- **Enums:** Use native backed enums in `Tueen\Telegram\Enums` for fixed parameter sets (ParseMode, ChatType, UpdateType, etc.).
- **Pipe Operator:** Ensure public methods and pipeline stages are callable-friendly and compatible with the Pipe Operator (`|>`).

---

## 2. Full Compatibility & Resilience
- All response types must inherit from `Tueen\Telegram\Types\Type`.
- Never break when Telegram introduces new fields or types; the dynamic fallback (`$extra` and base `Type` fallback) must remain intact.
- Support both camelCase property access (`$msg->messageId`) and snake_case ArrayAccess (`$msg['message_id']`).

---

## 3. Dual Error Handling & Universal `ok()` Guarantee
- All API types inherit from `Type` and provide `ok(): bool` and `isOk(): bool` (returning `true`).
- Under `ErrorHandlingMode::EXCEPTION` (default), throw typed exceptions (`ApiException`, `RateLimitException`, etc.).
- Under `ErrorHandlingMode::ERROR_OBJECT`, return typed `Tueen\Telegram\Types\Error` instances (where `ok()` returns `false`) for non-throwing flows.
- Internal/cURL errors captured via `withCatchAllErrors()` must receive negative error codes to distinguish from HTTP errors.

---

## 4. Method Design
- Method classes extend `Tueen\Telegram\Methods\Method`.
- Annotate with `#[ApiMethod('methodName')]` and `#[ReturnType(ReturnType::class)]`.
- Required parameters must always precede optional parameters in constructors.

---

## 5. File Transfers & Progress
- Always wrap uploadable payloads in `Tueen\Telegram\Types\Custom\InputFile` (`fromPath`, `fromResource`, `fromString`, `fromStream`).
- Provide progress callback hooks for all file uploads and downloads:
  `fn(int $bytesTransferred, int $totalBytes, float $percentage): void`
- Use streaming sinks for file downloads to prevent memory exhaustion.

---

## 6. Running Modes
- Support interchangeable execution strategies via `RunningModeInterface` (`WebhookMode`, `PollingMode`).
- `WebhookMode`: Verify secret token (`X-Telegram-Bot-Api-Secret-Token`), handle raw payload parsing, and allow immediate response flushing with `safeResponse()`.
- `PollingMode`: Handle offset tracking (`update_id + 1`) and automatic error backoff.

---

## 7. PSR Compliance
- Follow PSR-12 coding standard.
- Follow PSR-7 (HTTP Message), PSR-17 (HTTP Factory), PSR-18 (HTTP Client), and PSR-3 (LoggerInterface).

---

## 8. Documentation & Spec Synchronization (Mandatory)
- Whenever any feature, class, enum, configuration option, or client behavior is modified:
  - Keep `docs/` completely updated with full documentation, guides, and real-world examples.
  - When the Bot API schema is updated, ensure `Telegram::BOT_API_VERSION` is synchronized across the facade and mixin contracts.
  - Only modify what is strictly necessary, preserving conciseness and accuracy.
