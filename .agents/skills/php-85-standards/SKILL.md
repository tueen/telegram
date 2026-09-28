---
name: php-85-standards
description: >-
  Rules and best practices for PHP 8.5 features, specifically the Pipe Operator (|>),
  clone with expressions, #[\NoDiscard] attribute, URI Extension, Persistent cURL Share Handles,
  array_first()/array_last(), and closures in constant expressions.
---

# PHP 8.5 Standards & Language Features Guide

This skill defines the coding conventions, standards, and rules for **PHP 8.5** features within the `tueen/telegram` codebase, grounded directly in the official [PHP 8.5 Release Announcement](https://www.php.net/releases/8.5/en.php).

---

## 🔀 1. Pipe Operator (`|>`)

The pipe operator allows chaining function and callable calls together left-to-right without intermediary variables or deep nesting.

### Syntax & Usage
```php
// Modern PHP 8.5+:
$slug = $title
    |> trim(...)
    |> (fn(string $str) => str_replace(' ', '-', $str))
    |> (fn(string $str) => str_replace('.', '', $str))
    |> strtolower(...);

// Telegram pipeline application:
$response = $updatePayload
    |> $telegram->parseUpdate(...)
    |> $middlewarePipeline->handle(...)
    |> $router->dispatch(...);
```

### Best Practices:
- Keep methods callable-friendly using first-class callables (`$this->method(...)`).
- Use parenthesized closures `(fn($x) => ...)` for one-off step transformations.

---

## 🧬 2. `clone with` Expressions

PHP 8.5 allows updating properties during object cloning by passing an associative array of property changes directly to `clone()`:

```php
// ✅ Modern PHP 8.5+ with-er pattern:
public function withTimeout(float $timeout): self
{
    return clone($this, [
        'timeout' => $timeout,
    ]);
}

public function withProxy(?string $proxy): self
{
    return clone($this, [
        'proxy' => $proxy,
    ]);
}
```

### Application in tueen/telegram:
- Immutable configuration objects (`Config`) and value objects can use `clone($this, [...])` instead of verbose reflection, constructor destructuring, or manual property duplication.

---

## ⚠️ 3. `#[\NoDiscard]` Attribute

The `#[\NoDiscard]` attribute instructs the PHP engine to verify that a function or method's return value is actually consumed. If ignored, PHP emits a warning.

```php
#[\NoDiscard]
public function withHeader(string $name, string $value): self
{
    return clone($this, [
        'headers' => [...$this->headers, $name => $value],
    ]);
}

#[\NoDiscard]
public function validateSecretToken(string $token): bool
{
    return hash_equals($this->secretToken, $token);
}
```

### Best Practices:
- Apply `#[\NoDiscard]` to immutable fluent builders and validation methods where accidentally ignoring the returned instance leads to silent bugs.
- If a caller intentionally wants to discard a `#[\NoDiscard]` return value, PHP 8.5 provides the explicit `(void)` cast: `(void) $obj->method();`.

---

## 🌐 4. Standard URI Extension (`Uri\Rfc3986\Uri`)

PHP 8.5 includes an always-available, standards-compliant URI parser powered by `uriparser` (RFC 3986) and `Lexbor` (WHATWG URL):

```php
use Uri\Rfc3986\Uri;

$uri = new Uri('https://api.telegram.org/bot123456:ABC/sendMessage');

$host = $uri->getHost();   // "api.telegram.org"
$path = $uri->getPath();   // "/bot123456:ABC/sendMessage"
$scheme = $uri->getScheme(); // "https"
```

### Best Practices:
- Prefer `Uri\Rfc3986\Uri` over `parse_url()` for parsing webhook URLs, custom API base URIs, and webhook callback targets.

---

## ⚡ 5. Persistent cURL Share Handles

PHP 8.5 adds `curl_share_init_persistent()`, allowing connection pools and DNS cache handles to survive beyond the end of a single request across PHP-FPM / persistent worker processes:

```php
$sh = curl_share_init_persistent([
    CURL_LOCK_DATA_DNS,
    CURL_LOCK_DATA_CONNECT,
    CURL_LOCK_DATA_SSL_SESSION,
]);

$ch = curl_init('https://api.telegram.org/bot' . $token . '/getMe');
curl_setopt($ch, CURLOPT_SHARE, $sh);
curl_exec($ch);
```

### Best Practices:
- In HTTP clients and webhook runners, persistent share handles eliminate redundant TLS handshakes to `api.telegram.org`.

---

## 🧩 6. Closures and First-Class Callables in Constant Expressions

Static closures and first-class callables can now be passed as attribute arguments, default property/parameter values, and class constants:

```php
class PipelineConfig
{
    public const \Closure DEFAULT_FILTER = static fn($update) => $update !== null;

    #[Middleware(static fn($req, $next) => $next($req))]
    public function handle() {}
}
```

---

## 🎯 7. `array_first()` and `array_last()` Functions

PHP 8.5 introduces built-in `array_first()` and `array_last()` which return the first or last value of an array, or `null` if empty:

```php
// PHP 8.5:
$firstUpdate = array_first($updates);
$lastUpdate  = array_last($updates);

// Easy fallback with null coalescing:
$latestOffset = array_last($updates)?->updateId ?? 0;
```

---

## 📋 8. Additional PHP 8.5 Enhancements

- **Fatal Error Backtraces:** Fatal errors (e.g. execution timeouts, memory limits) now output a detailed backtrace.
- **`#[\Override]` on Properties:** Can now verify property overrides from parent classes or traits.
- **Static Property Asymmetric Visibility:** `private(set) static int $counter = 0;` is fully supported.
- **Final Promoted Properties:** `public function __construct(final public string $name)`.
- **`Closure::getCurrent()`:** Simplifies recursion in anonymous callbacks.

---

## 🛠️ Verification Checklist

1. **Verify PHP 8.5+ Compatibility:**
   - Ensure `composer.json` declares `"php": ">=8.5.0"`.
2. **Lint with PHP 8.5 CLI:**
   ```powershell
   php -l <path-to-file>
   ```
3. **Execute Test Suite:**
   ```powershell
   vendor/bin/phpunit
   ```
