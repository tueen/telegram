# Code Generator & Schema Updates

`tueen/telegram` is uniquely powered by an automated code generator (`Tueen\Telegram\Generator\CodeGenerator`).

Rather than manually writing hundreds of classes when Telegram releases new API versions (e.g. 10.3, 10.4), the generator scrapes and parses the official Telegram Bot API specification and generates **100% strictly typed PHP 8.4 & 8.5 classes**.

---

## 🏛️ Architecture of the Generator

The generator produces three core sets of files:
1. **Methods (`src/Methods/`)**: All 185 API endpoints with constructor arguments, docblocks, parameter validation, and PHP attributes (`#[ApiMethod]`, `#[ReturnType]`, `#[Field]`).
2. **Types (`src/Types/`)**: All 400+ Telegram objects using PHP 8.4 asymmetric visibility (`public private(set)`), array casting (`#[ArrayOf]`), and forward compatibility.
3. **Enums (`src/Enums/`)**: 51 Backed Enums with strict typing.
4. **IDE Contract Mixin (`src/Contracts/TelegramMethods.php`)**: An auto-generated docblock mixin detailing every method signature for instant IDE completion and static analysis.

---

## 🚀 Running the Generator

### Step 1: Download or Update the Schema
The generator parses schema definitions from [arknode-tech/telegram-bot-api-spec](https://github.com/arknode-tech/telegram-bot-api-spec) or the official Telegram documentation.

Save the latest schema JSON into `scratch/api.json` or `generator/api.json`.

### Step 2: Execute Generation Script

Run the generator via CLI:

```bash
php -r "require 'vendor/autoload.php'; (new Tueen\Telegram\Generator\CodeGenerator())->generate('scratch/api.json', 'src/');"
```

### Step 3: Validate and Format

After code generation, verify syntax integrity:

```bash
composer lint
```

And run the full PHPUnit test suite:

```bash
vendor/bin/phpunit
```

---

## 🛡️ Forward-Compatibility Guarantee

Even when a new Telegram Bot API update is released before the library is regenerated:
- **Unknown Method Parameters** can still be passed dynamically as named arguments or variadic `$extra`.
- **Unknown Type Properties** are stored in `$extra` and accessible via `$type->unknown_prop` or `$type['unknown_prop']`.
- **Unknown Response Objects** gracefully fall back to `Tueen\Telegram\Types\Type` without throwing deserialization exceptions.
