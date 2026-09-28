---
name: php-84-standards
description: >-
  Rules and best practices for modern PHP 8.4 & 8.5 features in tueen/telegram,
  specifically Asymmetric Visibility (omitting redundant 'public' on private(set)/protected(set)),
  Property Hooks, typed class constants, and new language constructs.
---

# PHP 8.4 & 8.5 Standards & Asymmetric Visibility Runbook

This skill defines the mandatory coding conventions and rules for modern PHP 8.4 and PHP 8.5 features within the `tueen/telegram` codebase.

---

## 🔒 1. Asymmetric Visibility (`private(set)` & `protected(set)`)

PHP 8.4 introduces asymmetric visibility, allowing separate visibility modifiers for read and write access on object properties.

### The Redundancy Rule (CRITICAL)

In PHP 8.4, if no main read-visibility modifier is specified, **it defaults to `public`**.

```php
// ✅ CORRECT (Idiomatic PHP 8.4+):
private(set) string $title;
private(set) ?User $user = null;
protected(set) int $offset = 0;

// ❌ INCORRECT (Anti-pattern - Triggers IDE inspection "Visibility modifier can be removed"):
public private(set) string $title;
public protected(set) int $offset = 0;
```

### Why IDEs Flag `public private(set)`:
IDEs like PhpStorm and static analysis tools (PHPStan, Psalm) issue a inspection warning:
> *"Visibility modifier can be removed"* (Redundant visibility modifier).

Specifying `public` before `private(set)` or `protected(set)` adds unnecessary boilerplate because `public` read-access is already the default behavior of the language specification.

### Guidelines for tueen/telegram:
1. **API Response Types (`src/Types/`):**
   - Every deserialized response field must use `private(set)`:
     ```php
     #[Field('file_id', required: true)]
     private(set) string $fileId;
     ```
2. **Generator Rules (`src/Generator/CodeGenerator.php`):**
   - When compiling property lines for generated types, always emit `private(set)` directly without `public`.
3. **When to use `protected(set)`:**
   - Use `protected(set)` when child or extending classes within the library need write access, but external callers should only read the property.

---

## 🪝 2. Property Hooks (`get` & `set`)

PHP 8.4 Property Hooks eliminate traditional getter and setter boilerplate.

### Computed Properties
Use property hooks for computed values derived from raw data:
```php
public UpdateType $type {
    get => $this->resolveType();
}

public bool $isCommand {
    get => str_starts_with($this->text ?? '', '/');
}
```

### Backed Properties with Validation / Normalization
When a property stores data but requires normalization:
```php
public string $username {
    set (string $value) => ltrim($value, '@');
}
```

---

## 🏷️ 3. Typed Class Constants

In PHP 8.3+, class constants must always be strictly typed:
```php
public const string BOT_API_VERSION = '10.3';
public const string API_VERSION = self::BOT_API_VERSION;
```

---

## 🔀 4. PHP 8.5 Pipe Operator (`|>`)

- Tueen clients and middleware pipelines must remain clean callable-friendly so callers can pipe operations:
```php
$result = $payload
    |> $telegram->parseUpdate(...)
    |> $dispatcher->dispatch(...);
```

---

## 🛠️ Verification & Anti-Regression Checklist

Before committing changes:

1. **Verify No Redundant `public private(set)` Exists:**
   ```powershell
   Get-ChildItem -Path "src", "tests" -Recurse -Filter "*.php" | Select-String -Pattern "public (?:private|protected)\(set\)"
   ```
   *(Expected output: empty / 0 matches)*

2. **Run Syntax Check on All Classes:**
   ```powershell
   php scratch/validate_all.php
   ```

3. **Run PHPUnit Test Suite:**
   ```powershell
   vendor/bin/phpunit
   ```
