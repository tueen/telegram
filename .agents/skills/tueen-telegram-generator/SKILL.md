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

- **Source Schema:** `resources/api.json` (or latest Telegram Bot API JSON schema).
- **Target Folders:**
  - `src/Types/` (all Telegram Bot API types, response models, and polymorphic unions)
  - `src/Methods/` (all Telegram Bot API method classes)
  - `src/Contracts/TelegramMethods.php` (complete 185-method mixin contract for IDE autocompletion)
- **Runner Script:** `bin/generate.php`
- **Compiler Class:** `Tueen\Telegram\Generator\CodeGenerator` (`tools/generator/CodeGenerator.php`)

---

## Procedures

### 1. Update the API Schema

When Telegram releases a new Bot API version:
1. Run the specification updater (which invokes the local Python scraper `tools/scraper/scrape.py` against `https://core.telegram.org/bots/api`):
   ```powershell
   composer update-api
   # or directly:
   php bin/update_spec.php
   ```
2. Alternatively, run the Python scraper standalone:
   ```powershell
   python tools/scraper/scrape.py --output resources/api.json
   ```
3. Inspect the version and new methods/types:
   ```powershell
   php -r '$d = json_decode(file_get_contents("resources/api.json"), true); echo $d["version"] . " - " . count($d["methods"]) . " methods, " . count($d["types"]) . " types\n";'
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

### 5. API Version Constant Synchronization
- The supported Bot API version is declared via `Tueen\Telegram\Telegram::BOT_API_VERSION` (and `Telegram::API_VERSION`).
- Running `php bin/generate.php` automatically extracts the schema `version` and updates `Telegram::BOT_API_VERSION` in `src/Telegram.php` as well as the mixin docblocks in `src/Contracts/TelegramMethods.php`.
- Always verify that `tests/Unit/TelegramClientTest.php` (`testBotApiVersionConstant`) reflects the new version and passes.

