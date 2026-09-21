# Tueen Telegram Rules & Standards

When creating, extending, or maintaining code in `tueen/telegram`:

1. **PHP 8.4+ / 8.5+ Strict Typing**:
   - Always declare `declare(strict_types=1);`.
   - Prefer asymmetric visibility (`public private(set)`) for properties that are populated from API responses.
   - Use PHP 8.4 property hooks where custom getter/setter transformation is needed.
   - Use native enums in `Tueen\Telegram\Enums` for fixed parameter sets.

2. **Full Compatibility & Resilience**:
   - All response types must inherit from `Tueen\Telegram\Types\Type`.
   - Never break when Telegram introduces new fields or types; the dynamic fallback in `Type` must remain intact.
   - Support both camelCase property access and snake_case ArrayAccess.

3. **Method Design**:
   - Method classes extend `Tueen\Telegram\Methods\Method`.
   - Annotate with `#[ApiMethod('methodName')]` and `#[ReturnType(ReturnType::class)]`.
   - Required parameters must always precede optional parameters in constructors.

4. **Progress & Streaming**:
   - Provide progress hooks for all large payload transfers (both upload and download).
   - Use streaming sinks (`RequestOptions::SINK`) for file downloads to prevent memory exhaustion.

5. **PSR Compliance**:
   - Follow PSR-12 coding standard.
   - Follow PSR-7 (HTTP Message), PSR-17 (HTTP Factory), PSR-18 (HTTP Client), PSR-3 (LoggerInterface).
