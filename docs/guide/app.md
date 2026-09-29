# App Orchestrator

`tueen/telegram` provides the **`App`** orchestrator, a high-level application layer designed for effortless zero-config bot development, instant deployment on shared hosting or CLI, automated conversation flow session storage, and an interactive **Web Setup & Management Dashboard**.

---

## 🚀 Quick Start in 60 Seconds

Create a standard bot project directory:

```text
my-bot/
├── config.php        # Configuration array (token, secret, etc.)
├── routes.php        # Bot commands and update handlers
├── index.php         # Entry point script
└── storage/          # Automatically created for flow sessions
    └── flow/
```

### 1. `config.php`

Return your configuration array:

```php
<?php

declare(strict_types=1);

return [
    'token' => '123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ',
    'secret_token' => 'super-secret-token', // Optional Telegram secret token header
    'mode' => 'auto',                      // 'auto', 'polling', or 'webhook'
];
```

### 2. `routes.php`

Define your commands and message handlers:

```php
<?php

declare(strict_types=1);

use Tueen\Telegram\App;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/** @var App $app */
/** @var Telegram $bot */

$app->onCommand('start', function (Update $update, Telegram $bot) {
    $bot->sendMessage(text: "👑 Welcome to my royal Telegram Bot!");
});

$app->onMessage('ping', function (Update $update, Telegram $bot) {
    $bot->sendMessage(text: "pong 🏓");
});
```

### 3. `index.php`

Initialize the App and call `run()`:

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

## ⚡ Adaptive Execution Environments

When `$app->run()` is invoked, it intelligently detects the execution context:

| Environment | Trigger | Behavior |
| :--- | :--- | :--- |
| **CLI / Terminal** | `php index.php` | Runs interactive Long-Polling or CLI helper commands (`webhook:set`, etc.). |
| **HTTP POST** | Incoming Telegram Webhook | Verifies `secret_token`, executes update handlers/flows, and returns `200 OK`. |
| **HTTP GET** | Opened in Web Browser | Launches the **Royal Web Dashboard** or **First-Time Setup Wizard**. |

---

## 🌐 Web Setup Wizard & Management Console

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

## 💾 Automatic Flow State Storage (`storage/flow`)

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

## 💻 CLI Commands

You can run administrative operations directly from your terminal:

```bash
# Start polling mode with a styled welcome banner
php index.php

# Set webhook URL
php index.php webhook:set https://example.com/index.php

# Set webhook and drop pending updates
php index.php webhook:set https://example.com/index.php --drop

# Delete active webhook
php index.php webhook:delete

# Delete webhook and drop all pending updates
php index.php webhook:delete --drop

# Inspect current webhook status and errors
php index.php webhook:info

# View bot profile and permissions
php index.php bot:info

# Run system self-diagnostics
php index.php doctor

# Show help
php index.php --help
```

---

## 🔑 Environment Variables (`.env`)

`App` includes a zero-dependency `.env` reader that automatically loads configuration from your project's `.env` file if present:

```ini
TELEGRAM_BOT_TOKEN="123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ"
TELEGRAM_SECRET_TOKEN="my-secret-token"
TELEGRAM_WEBHOOK_URL="https://yourdomain.com/index.php"
```

If these keys are present in `.env`, `App` automatically uses them as default fallback values without requiring hardcoded tokens in `config.php`.

You can also read environment values using `Tueen\Telegram\App\Env::get('KEY', default: null)`.

---

## 📁 Automatic Controller Discovery

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

## 🩺 System Diagnostics (`doctor`)

To troubleshoot webhook issues, SSL certificate mismatches, or file permission errors on shared hosting:

- Run `php index.php doctor` in your terminal to see a colored diagnostic status table.
- Or open your bot in a browser to inspect the **System Diagnostics (Doctor)** card in the Web Dashboard.

It verifies:
- PHP version compatibility (PHP 8.4+).
- Required extensions (`curl`, `json`, `mbstring`, `openssl`).
- Flow storage permissions (`storage/flow` writable).
- Telegram Bot API server reachability and latency.
- Webhook URL HTTPS compliance.

