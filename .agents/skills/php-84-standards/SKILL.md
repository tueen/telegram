---
name: php-84-standards
description: >-
  Rules and best practices for PHP 8.4 features, specifically Property Hooks,
  Asymmetric Visibility (omitting redundant 'public'), new array_* functions (array_find, array_any, array_all),
  method chaining on new without parentheses, and #[\Deprecated] attribute.
---

# PHP 8.4 Standards & Language Features Guide

This skill defines the coding conventions, standards, and rules for **PHP 8.4** features within the `tueen/telegram` codebase, grounded directly in the official [PHP 8.4 Release Announcement](https://www.php.net/releases/8.4/en.php).

---

## 🪝 1. Property Hooks (`get` & `set`)

Property hooks provide support for computed and validated properties that are natively recognized by IDEs and static analysis tools without getter/setter boilerplate.

### Computed Properties
Use property hooks for computed values derived from object state:
```php
public UpdateType $type {
    get => $this->resolveType();
}

public bool $isCommand {
    get => str_starts_with($this->text ?? '', '/');
}
```

### Backed Properties with Validation / Normalization
When a property stores data but requires normalization or pre-processing:
```php
public string $username {
    set (string $value) => ltrim($value, '@');
}

public string $countryCode {
    set (string $countryCode) {
        $this->countryCode = strtoupper($countryCode);
    }
}
```

---

## 🔒 2. Asymmetric Visibility (`private(set)` & `protected(set)`)

The scope to write to a property may be controlled independently from the scope to read the property.

### The Redundancy Rule (CRITICAL)
In PHP 8.4, if no explicit read-visibility modifier is specified, **it defaults to `public`**.
Specifying `public private(set)` triggers IDE inspection warnings (*"Visibility modifier can be removed"*).

```php
// ✅ CORRECT (Idiomatic PHP 8.4+):
private(set) int $id;
private(set) ?User $user = null;
protected(set) int $offset = 0;

// ❌ INCORRECT (Anti-pattern - triggers inspection warning):
public private(set) int $id;
public protected(set) int $offset = 0;
```

### Guidelines for tueen/telegram:
1. **API Response Types (`src/Types/`):**
   - Every deserialized response field must use `private(set)`:
     ```php
     #[Field('file_id', required: true)]
     private(set) string $fileId;
     ```
2. **Generator Rules (`tools/generator/CodeGenerator.php`):**
   - The code generator must always emit `private(set)` directly without `public`.
3. **Internal State (`protected(set)`):**
   - Use `protected(set)` when child or extending classes need write access, but external callers should only read the property.

---

## 🔍 3. New `array_*()` Functions

PHP 8.4 introduces modern functional search utilities for arrays:

- `array_find(array $array, callable $callback): mixed` — returns the first matching element, or `null`.
- `array_find_key(array $array, callable $callback): mixed` — returns the key of the first matching element, or `null`.
- `array_any(array $array, callable $callback): bool` — returns `true` if at least one element satisfies the predicate.
- `array_all(array $array, callable $callback): bool` — returns `true` if all elements satisfy the predicate.

```php
// Find specific update type
$hasPhoto = array_any($updates, fn(Update $u) => $u->message?->photo !== null);

// Find first command message
$firstCommand = array_find($messages, fn(Message $m) => $m->isCommand);
```

---

## ⛓️ 4. Method Chaining on `new` Without Parentheses

In PHP 8.4, you can chain methods directly onto `new ClassName()->method()` without wrapping the `new` expression in extra parentheses:

```php
// ✅ Modern PHP 8.4+:
$request = new Request('POST', $url)->withHeader('Accept', 'application/json');

// ❌ Pre-8.4 boilerplate:
$request = (new Request('POST', $url))->withHeader('Accept', 'application/json');
```

---

## 🏷️ 5. `#[\Deprecated]` Attribute

Deprecations are declared natively using the `#[\Deprecated]` attribute instead of solely relying on docblocks:

```php
#[\Deprecated(message: 'Use $message->findAnyText() instead', since: '1.0.0')]
public function getTextOrCaption(): ?string
{
    return $this->findAnyText();
}
```

---

## 🔢 6. BCMath Object API & Typed Class Constants

- Class constants must always have strict type declarations (`public const string BOT_API_VERSION = '10.3';`).
- Use `BcMath\Number` for arbitrary precision calculations where needed.

---

## 🛠️ Verification Checklist

1. **Verify No Redundant `public private(set)` Exists:**
   ```powershell
   Get-ChildItem -Path "src", "tests" -Recurse -Filter "*.php" | Select-String -Pattern "public (?:private|protected)\(set\)"
   ```
2. **Verify PHP 8.4 Syntax:**
   ```powershell
   php -l <path-to-file>
   ```
