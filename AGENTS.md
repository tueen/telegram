# AGENTS.md - Developer & Agent Guide for `tueen/telegram`

Welcome to `tueen/telegram` — **The Royal Telegram Bot API Client for Tueen**, a proud cornerstone of the **Tueen Ecosystem** (*"The Queen of Telegram"*).

This file serves as the **Master Architectural Reference and Sitemap** for AI agents and human contributors. Detailed coding standards and domain procedures are organized into specialized skills within [`.agents/skills/`](./.agents/skills/) and rules within [`.agents/rules/`](./.agents/rules/).

---

## 👑 1. Library Identity & Mission

- **Package Name:** `tueen/telegram`
- **Slogan:** *The Royal Telegram Bot API Client for Tueen*
- **Target Runtime:** PHP 8.5+ (strict types, property hooks, asymmetric visibility, pipe operator, modern pipeline pattern).
- **Coverage:** Complete Telegram Bot API 10.3 (all 185 methods, 400 types, and property enums).
- **Bot API Version Constant:** `Telegram::BOT_API_VERSION` (alias `Telegram::API_VERSION`).

---

## 🏛️ 2. Architecture & Directory Structure

```
tueen/telegram/
├── src/
│   ├── Telegram.php                   # Main client facade entry point (@mixin TelegramMethods)
│   ├── Contracts/
│   │   └── TelegramMethods.php        # Method signatures mixin (all 185 Bot API methods for IDE)
│   ├── Config.php                     # Immutable client configuration
│   ├── ConfigBuilder.php              # Fluent configuration builder
│   ├── Running/                       # Running modes (WebhookMode, PollingMode)
│   │   ├── RunningModeInterface.php
│   │   ├── WebhookMode.php            # Webhook runner (secret_token validation, safeResponse)
│   │   └── PollingMode.php            # Long-polling runner (offset tracking, auto-backoff)
│   ├── Types/                         # All Telegram Bot API types (400 types)
│   │   ├── Type.php                   # Base Type with universal ok() check, dynamic fallback & ArrayAccess
│   │   ├── Error.php                  # Typed error object for non-throwing error handling
│   │   ├── Concerns/                  # Property hooks & helper traits (HasUpdateHelpers, HasMessageHelpers)
│   │   └── Custom/InputFile.php       # Multipart file wrapper (fromPath, fromResource, fromString, fromStream)
│   ├── Methods/                       # All Telegram Bot API methods (185 methods)
│   │   └── Method.php                 # Base Method class with multipart & serialization
│   ├── Enums/                         # Standard Backed Enums (ParseMode, ChatType, UpdateType, etc.)
│   ├── Client/                        # HTTP client abstraction (PSR-18 / Guzzle 7)
│   ├── Pipeline/                      # Extensible middleware pipeline (RetryMiddleware, LoggingMiddleware)
│   ├── Attributes/                    # Declarative attributes (ApiMethod, ReturnType, Field, ArrayOf)
│   ├── Exceptions/                    # Typed exception hierarchy
│   └── Generator/                     # Code generator compiled from api.json schema
├── bin/
│   ├── generate.php                   # Generates PHP classes from resources/api.json
│   └── update_spec.php                # Scrapes Telegram Bot API docs and regenerates classes
├── resources/
│   └── api.json                       # Machine-readable Telegram Bot API specification
├── docs/                              # VitePress interactive documentation
├── tests/                             # PHPUnit test suite
├── composer.json
└── README.md
```

---

## 🧭 3. Specialized Agent Skills (`.agents/skills/`)

Detailed operational guidelines, code examples, and conventions are encapsulated in the following dedicated skills:

| Skill | Path | Purpose |
| :--- | :--- | :--- |
| **`tueen-telegram-development`** | [`.agents/skills/tueen-telegram-development/`](./.agents/skills/tueen-telegram-development/) | Core development procedures, pipeline middlewares, running modes, file transfers, and lifecycle hooks. |
| **`tueen-telegram-generator`** | [`.agents/skills/tueen-telegram-generator/`](./.agents/skills/tueen-telegram-generator/) | Automation of scraping, regenerating, and updating Bot API methods, types, and enums from schema. |
| **`php-85-standards`** | [`.agents/skills/php-85-standards/`](./.agents/skills/php-85-standards/) | PHP 8.5 language conventions (Pipe operator `\|>`, `clone with`, `#[\NoDiscard]`, URI extension, persistent cURL handles). |
| **`php-84-standards`** | [`.agents/skills/php-84-standards/`](./.agents/skills/php-84-standards/) | PHP 8.4 language conventions (Property Hooks, Asymmetric Visibility without redundant `public`, `array_*()` utilities). |

---

## 💎 4. Core Architectural Tenets

When designing or modifying features in this codebase, adhere to these four immutable pillars:

1. **Modern Strict Architecture:**
   - 100% strict types (`declare(strict_types=1);`).
   - Clean asymmetric visibility (`private(set)` across response types, never `public private(set)`).
   - Property hooks for normalized/computed properties instead of repetitive getters/setters.
   - Callable-friendly APIs supporting the Pipe Operator (`|>`) and fluent middleware pipeline.

2. **Universal `ok()` & Dual Error Modes:**
   - All response types inherit from `Type` and provide `ok(): bool` and `isOk(): bool`.
   - Caller chooses between `ErrorHandlingMode::EXCEPTION` (default) and `ErrorHandlingMode::ERROR_OBJECT` (returns typed `Error` objects).

3. **Bulletproof Forward-Compatibility:**
   - Unknown future Telegram fields are stored in `$extra` and accessible dynamically via property access and `ArrayAccess`.
   - Unknown objects fallback gracefully to the base `Type` class, preventing runtime deserialization crashes.

4. **Documentation & Spec Synchronization Rule:**
   - Whenever any feature, method, or enum is modified: keep [`docs/`](./docs) fully synchronized.
   - When updating the Bot API specification via `bin/generate.php`, ensure `Telegram::BOT_API_VERSION` is synchronized across the facade and mixin contracts.

---

## 🛠️ 5. Essential Commands

| Task | Command |
| :--- | :--- |
| **Lint & Syntax Validation** | `composer lint` |
| **Run Unit Tests** | `composer test` (or `vendor/bin/phpunit`) |
| **Regenerate Classes from Schema** | `composer generate` |
| **Fetch Online Spec & Regenerate** | `composer update-api` |
| **Run Documentation Server** | `cd docs && npm run docs:dev` |
