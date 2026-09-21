---
name: tueen-telegram-generator
description: >-
  Automates the scraping, updating, and regenerating of all Telegram Bot API Types, Methods, and Enums
  for the tueen/telegram client library from schema definitions. Use when updating the Bot API specification
  or regenerating classes.
---

# Tueen Telegram Code Generator Runbook

This skill guides the AI assistant in updating, maintaining, and regenerating the complete Telegram Bot API specification (Methods, Types, and Enums) for the `tueen/telegram` library.

---

## Generator Overview

The generator transforms the machine-readable Telegram Bot API JSON schema into strictly typed PHP 8.4 & 8.5 classes.

- **Source Schema:** `scratch/api.json` (or latest Telegram Bot API JSON schema).
- **Target Folders:**
  - `src/Types/` (all Telegram Bot API types, response models, and polymorphic unions)
  - `src/Methods/` (all Telegram Bot API method classes)
- **Runner Script:** `bin/generate.php`
- **Compiler Class:** `Tueen\Telegram\Generator\CodeGenerator`

---

## Procedures

### 1. Update the API Schema

When Telegram releases a new Bot API version:
1. Ensure the latest schema JSON is stored in `scratch/api.json`.
2. Inspect the version and new methods/types:
   ```powershell
   php -r '$d = json_decode(file_get_contents("scratch/api.json"), true); echo $d["version"] . PHP_EOL;'
   ```

### 2. Run the Generator

Execute the generation command from the repository root:
```powershell
php bin/generate.php
```

Expected output:
```text
Starting Telegram Bot API generator...
Generating Types...
Generated 400 types.
Generating Methods...
Generated 185 methods.
Done generating code.
All classes generated successfully!
```

### 3. Rebuild Autoload Files

Update Composer autoloader to discover newly generated classes:
```powershell
composer dump-autoload
```

### 4. Verify & Validate Code

1. Run the high-speed in-process syntax validator:
   ```powershell
   php scratch/validate_all.php
   ```
2. Run the entire test suite to ensure zero regressions:
   ```powershell
   vendor/bin/phpunit
   ```
