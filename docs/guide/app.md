# Zero-Config App & Web Dashboard

`Tueen\Telegram\App` is the high-level application bootstrapper and orchestrator for `tueen/telegram`. It eliminates boilerplate configuration, automatically sets up file-based conversation flow storage, auto-discovers attribute controllers, and provides a built-in Web Setup Wizard and CLI tooling.

---

## 🚀 1. The Single-File Entry Point

With `App`, you can launch a complete, production-ready Telegram bot from a single file:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Tueen\Telegram\App;

$app = App::create(__DIR__);
$app->run();
```

::: tip Flexible Entry Point Naming
While `index.php` is standard, you can freely name your entry point file whatever you want (such as `bot.php`, `update.php`, or `webhook.php`). `App::create(__DIR__)` dynamically inspects the executing script and automatically resolves the correct public webhook URL.
:::

---

## ⚡ 2. Adaptive Execution Environments

When `$app->run()` is invoked, it intelligently detects the execution context:

<ApiGroup description="Automatic runtime detection based on server sapi and request method.">
  <ApiCard
    sig="CLI / Terminal"
    badge="CLI Mode"
    desc="Triggered by 'php index.php'. Runs interactive Long-Polling or CLI helper commands (webhook:set, doctor, etc.)."
  />
  <ApiCard
    sig="HTTP POST (Webhook)"
    badge="Webhook Mode"
    desc="Triggered by incoming Telegram webhook requests. Validates secret_token, dispatches update handlers and flows, and returns 200 OK."
  />
  <ApiCard
    sig="HTTP GET (Browser)"
    badge="Web Dashboard"
    desc="Triggered when index.php is opened in a web browser. Launches the interactive Web Setup Wizard or live Management Console."
  />
</ApiGroup>

---

## 🌐 3. Web Setup Wizard & Management Console

Opening `index.php` in a web browser activates the **Royal Web Dashboard**:

### 1. First-Time Setup Wizard (When Token is Empty)
If no bot token is configured in `config.php`:
- Displays a setup form to enter your **Bot Token** (from `@BotFather`).
- Automatically detects your public webhook URL (`https://yourdomain.com/index.php`).
- Includes a button to generate a secure 32-character random `secret_token`.
- Tests connection with `getMe()` and automatically sets the webhook via `setWebhook()`.
- Automatically writes credentials into `config.php`.

### 2. Live Management Console (When Configured)
Once configured, visiting the URL in a browser displays a real-time status console:
- **Bot Profile:** Name, `@username`, Bot ID, group permissions, and Bot API ping latency.
- **Webhook Telemetry:** Active webhook URL, pending update queue count, SSL certificate type, max connections, and any recent delivery errors from Telegram servers.
- **One-Click Actions:**
  - 🎯 **Set / Update Webhook:** Sets the webhook to the current URL with optional `drop_pending_updates`.
  - 🗑️ **Delete Webhook:** Removes the webhook from Telegram servers.
  - 🔄 **Refresh:** Reloads live status from Telegram Bot API.
- **Storage & Flow Metrics:** Displays active flow session files and storage path.

### Securing the Dashboard
To protect the web dashboard with a password, specify `setup.password` in your `config.php`:

```php
return [
    'token' => '...',
    'setup' => [
        'enabled' => true,
        'password' => 'my-secure-dashboard-password',
    ],
];
```

---

## 💾 4. Automatic Flow State Storage (`storage/flow`)

`App` automatically configures persistent conversation flow storage:

- By default, it creates `$basePath . '/storage/flow'` and attaches `FileStateStore`.
- Protects the directory against direct web browser access with an Apache `.htaccess` (`Deny from all`) and an `index.php` fallback (`403 Forbidden`).
- Multi-step conversational flows (`$bot->startFlow(...)`) automatically persist state without any external database or Redis setup.

To customize the flow storage path, configure `flow_storage` in `config.php`:

```php
return [
    'token' => '...',
    'flow_storage' => '/var/data/telegram_flows',
];
```

---

## 📁 5. Automatic Controller Discovery

If your bot project has a `controllers/` directory in its project root, `App` automatically scans all PHP files inside it. Any class decorated with routing attributes (`#[OnCommand]`, `#[OnCallbackQuery]`, `#[OnMessage]`, `#[OnInlineQuery]`, `#[OnUpdate]`) is registered automatically with zero boilerplate!

```php
// controllers/StartController.php
namespace App\Controllers;

use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Telegram;

class StartController
{
    #[OnCommand('start')]
    public function start(Update $update, Telegram $bot): void
    {
        $bot->sendMessage(text: "Hello from auto-discovered controller!");
    }
}
```

---

## 🩺 6. System Diagnostics (`doctor`)

To troubleshoot webhook issues, SSL certificate mismatches, or file permission errors on shared hosting:

- Run `php index.php doctor` in your terminal to see a colored diagnostic status table.
- Or open your bot in a browser to inspect the **System Diagnostics (Doctor)** card in the Web Dashboard.

It verifies:
- PHP version compatibility (PHP 8.4+).
- Required extensions (`curl`, `json`, `mbstring`, `openssl`).
- Flow storage permissions (`storage/flow` writable).
- Telegram Bot API server reachability and latency.
- Webhook URL HTTPS compliance.

---

## 🧭 7. App & CLI API Reference

Below is the complete reference of methods and properties available on `Tueen\Telegram\App`, along with terminal CLI commands.

### 🏛️ `App` Class (`Tueen\Telegram\App`)

<ApiGroup description="High-level application orchestrator methods and property hooks.">
  <ApiCard
    sig="App::create(string $basePath, array $config = []): static"
    returns="App"
    badge="Factory"
    desc="Initializes and boots the App instance for the given project base root path."
  />
  <ApiCard
    sig="App::boot(string $basePath, array $config = []): static"
    returns="App"
    badge="Alias"
    aliasFor="App::create()"
    desc="Expressive shorthand alias for App::create()."
  />
  <ApiCard
    type="property"
    sig="private(set) Telegram $bot"
    returns="Telegram"
    badge="Asymmetric Visibility"
    desc="Underlying Telegram client facade instance."
  />
  <ApiCard
    type="property"
    sig="public Router $router"
    returns="Router"
    badge="Property Hook"
    desc="Active update Router instance proxied directly from $bot->router."
  />
  <ApiCard
    type="property"
    sig="public FlowManager $flowManager"
    returns="FlowManager"
    badge="Property Hook"
    desc="Active FlowManager state orchestrator proxied directly from $bot->flowManager."
  />
  <ApiCard
    type="property"
    sig="private(set) string $basePath"
    returns="string"
    badge="Asymmetric Visibility"
    desc="Resolved absolute root directory path of the bot project."
  />
  <ApiCard
    type="property"
    sig="private(set) string $storagePath"
    returns="string"
    badge="Asymmetric Visibility"
    desc="Directory path for application storage ($basePath/storage)."
  />
  <ApiCard
    type="property"
    sig="private(set) string $flowStoragePath"
    returns="string"
    badge="Asymmetric Visibility"
    desc="Directory path for persistent conversational flow state files ($basePath/storage/flow)."
  />
  <ApiCard
    sig="run(mixed ...$handlers): mixed"
    returns="mixed"
    badge="Runner"
    desc="Executes the bot using adaptive environment detection (CLI polling, HTTP webhook, or Web dashboard)."
  />
  <ApiCard
    sig="reply(string|Text $text, mixed ...$args): mixed"
    returns="mixed"
    badge="Helper"
    desc="Quick reply helper to send a text message to the active chat in context."
  />
  <ApiCard
    sig="catch(string|callable $exceptionOrHandler, ?callable $handler = null): static"
    returns="static"
    badge="Error Handling"
    desc="Registers a global exception handler for incoming update processing errors."
  />
  <ApiCard
    sig="reloadConfig(array $overrides = []): static"
    returns="static"
    badge="Lifecycle"
    desc="Reloads configuration from config.php and rebuilds client and flow storage instances."
  />
</ApiGroup>

---

### 💻 CLI Administrative Commands

<ApiGroup description="Terminal operations executed via 'php index.php <command>'.">
  <ApiCard
    sig="php index.php"
    badge="CLI Command"
    desc="Starts interactive Long-Polling mode with real-time log output in terminal."
  />
  <ApiCard
    sig="php index.php webhook:set <url> [--drop]"
    badge="CLI Command"
    desc="Sets Telegram webhook URL with optional --drop flag to discard pending updates."
  />
  <ApiCard
    sig="php index.php webhook:delete [--drop]"
    badge="CLI Command"
    desc="Deletes active webhook from Telegram servers."
  />
  <ApiCard
    sig="php index.php webhook:info"
    badge="CLI Command"
    desc="Inspects current webhook status, URL, pending update count, and error telemetry."
  />
  <ApiCard
    sig="php index.php bot:info"
    badge="CLI Command"
    desc="Displays bot profile, username, Bot API ping latency, and group permissions."
  />
  <ApiCard
    sig="php index.php doctor"
    badge="CLI Command"
    desc="Runs complete system self-diagnostics (PHP version, extensions, writable storage, HTTPS)."
  />
</ApiGroup>
