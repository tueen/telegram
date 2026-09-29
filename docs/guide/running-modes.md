# Running Modes

`tueen/telegram` features an elegant execution strategy separating how updates are received from how they are processed.

You can switch between **Webhook Mode** and **Long-Polling Mode** seamlessly. In all modes, incoming updates are resolved, stored in the `$bot->update` property, and dispatched to your handler(s) with full type-safety.

---

## 🚀 Unified Execution with `$bot->run()`

The `$bot->run()` method is the central entry point for executing your bot. It inspects the configured `RunningMode` and dispatches incoming updates to your handlers.

### 1. Handler Signature
Handlers receive two parameters:
1. `Update $update` — The incoming update instance.
2. `Telegram $bot` — The `Telegram` client instance.

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

$bot = new Telegram('YOUR_BOT_TOKEN');

$bot->run(function (Update $update, Telegram $bot) {
    if ($update->message !== null) {
        $bot->sendMessage(
            chatId: $update->findChat()->id,
            text: "Hello! You said: {$update->message->findAnyText()}"
        );
    }
});
```

### 2. Invokable Handler Classes
Instead of inline closures, you can organize your logic into dedicated classes that implement `__invoke(Update $update, Telegram $bot)`:

```php
namespace App\Handlers;

use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class CommandHandler
{
    public function __invoke(Update $update, Telegram $bot): void
    {
        $message = $update->findMessage();
        if ($message?->isCommand && $message->getCommand() === 'start') {
            $bot->sendMessage(
                chatId: $update->findChat()->id,
                text: 'Welcome to Tueen!'
            );
        }
    }
}
```

Pass the class name string directly to `run()`:

```php
$bot->run(CommandHandler::class);
```

If a PSR-11 container (or callable resolver) is configured via `$bot->setContainer($container)` or `ConfigBuilder::withContainer($container)`, Tueen will resolve handler dependencies through your container automatically.

### 3. Multiple Handlers & Middleware Chains
You can pass multiple handlers as variadic arguments or as an array. Handlers run in sequential order:

```php
$bot->run(
    AuthMiddleware::class,
    CommandHandler::class,
    function (Update $update, Telegram $bot) {
        // Fallback logger
    }
);

// Or using an array:
$bot->run([AuthMiddleware::class, CommandHandler::class]);
```

You can also pre-register handlers using the fluent `$bot->handle(...)` method:

```php
$bot
    ->handle(AuthMiddleware::class)
    ->handle(CommandHandler::class)
    ->run();
```

> [!TIP]
> If any handler explicitly returns `false`, execution of subsequent handlers in the chain will stop immediately for that update.

---

## 📦 The `$bot->update` Property

The `Telegram` client maintains an internal `$update` property that defaults to `null`:

```php
private(set) ?Update $update = null;
```

- When `run()` executes under **Webhook Mode**, the incoming update is resolved from the HTTP request and assigned to `$bot->update`.
- When `run()` executes under **Long-Polling Mode**, each received update is assigned to `$bot->update` before being passed to handlers.

```php
// In a webhook controller:
$bot->run();

// Access the resolved update directly:
$chatId = $bot->update?->findChat()?->id;
```

---

## 1. Webhook Mode (`WebhookMode`)

In production environments (Nginx, Apache, Caddy, FrankenPHP, Laravel, Symfony), bots receive updates as incoming HTTP POST requests sent by Telegram.

### Basic Setup
```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Types\Update;

$bot = new Telegram('YOUR_BOT_TOKEN');

// Configure Webhook mode with optional secret token
$bot->setRunningMode(new WebhookMode(secretToken: 'your-secret-token'));

// Run and dispatch
$bot->run(function (Update $update, Telegram $bot) {
    if ($update->message) {
        $bot->sendMessage(
            chatId: $update->message->chat->id,
            text: 'Message received via Webhook!'
        );
    }
});
```

### Secret Token Verification
Telegram allows specifying a `secret_token` when setting your webhook (`setWebhook`). On every request, Telegram sends this token in the `X-Telegram-Bot-Api-Secret-Token` header.

`WebhookMode` automatically verifies this header:
```php
$mode = new WebhookMode(secretToken: 'my_super_secure_token');
$bot->setRunningMode($mode);

// If the header is missing or doesn't match, a TelegramException is thrown immediately,
// protecting your server from forged requests.
$bot->run(MyHandler::class);
```

### Fast Response (`safeResponse`)
Telegram expects your webhook endpoint to answer with an `HTTP 200 OK` within a few seconds. If your logic performs heavy tasks, call `safeResponse()` to immediately send HTTP 200 and detach the connection via `fastcgi_finish_request()`:

```php
$mode = new WebhookMode();
$mode->safeResponse(); // Sends HTTP 200, Content-Type: application/json, closes connection

// Continue heavy processing in the background:
// e.g. generate AI images, process database transactions, etc.
$bot->run(HeavyProcessingHandler::class);
```

### Integration with Frameworks (Laravel, Symfony, PSR-7)
```php
// In a Laravel Controller:
public function webhook(Request $request, Telegram $bot)
{
    $mode = new WebhookMode(
        secretToken: config('services.telegram.secret'),
        rawInput: $request->getContent(),
        headers: $request->headers->all()
    );

    $bot->setRunningMode($mode);

    $bot->run(function (Update $update, Telegram $bot) {
        // Handle update
    });

    return response()->json(['ok' => true]);
}
```

---

## 2. Long-Polling Mode (`PollingMode`)

During local development or for CLI background workers, Long-Polling continuously queries Telegram for new updates in an infinite loop.

### Continuous Listening
```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Types\Update;

$bot = new Telegram('YOUR_BOT_TOKEN');

// Set Polling mode
$polling = new PollingMode(
    timeout: 30,             // Long-polling timeout in seconds
    limit: 100,              // Updates per batch
    allowedUpdates: ['message', 'callback_query']
);

$bot->setRunningMode($polling);

// Starts infinite worker loop in CLI:
echo "Bot started polling...\n";
$bot->run(function (Update $update, Telegram $bot) {
    echo "Received update #{$update->updateId}\n";

    if ($update->message?->text === '/ping') {
        $bot->sendMessage(
            chatId: $update->message->chat->id,
            text: 'Pong!'
        );
    }
});
```

### Multi-Process Concurrency (`forkProcess`)
In high-throughput environments, you can process each incoming update in a separate forked process:

```php
$polling = new PollingMode(timeout: 30);

// Enable process forking (requires pcntl extension on CLI):
$polling->forkProcess(true);

$bot->setRunningMode($polling);
$bot->run(MyUpdateHandler::class);
```

When enabled, `PollingMode` forks a dedicated child process for each update (and automatically reaps completed children to avoid zombie processes). On environments where `pcntl_fork` is unavailable (e.g. Windows), it gracefully executes synchronously without crashing.

### Custom Process Dispatcher
You can customize how updates are dispatched to background processes (e.g. using worker pools, message queues, ReactPHP, Amp, or Swoole):

```php
$polling->setProcessDispatcher(function (Update $update, Telegram $bot, callable $next) {
    // E.g., dispatch to an async queue or background pool
    $next();
});
```

### Features of `PollingMode`:
1. **Automatic Offset Advancement:** Tracks `update_id + 1` automatically so updates are never processed twice.
2. **Per-Update Isolation & Resilience:** If a single update in a batch throws an uncaught exception, `PollingMode` logs the error, safely advances the offset, and continues processing remaining updates in the batch without crashing the long-polling daemon (configurable via `stopOnError(bool)`).
3. **Resilience & Backoff:** Automatically handles transient network interruptions with a configurable cooldown (`errorBackoffSeconds`).
4. **Graceful Shutdown:** You can call `$polling->stop()` from a signal handler (e.g. `SIGINT` / `SIGTERM`) to cleanly exit the polling loop.

### Manual Generator Iteration
If you prefer standard PHP `foreach` iteration:

```php
foreach ($bot->poll(timeout: 30) as $update) {
    // Process $update
}
```

---

## Native Telegram Bot API Method: `getUpdates`

The official Telegram Bot API method to fetch updates is `getUpdates` (plural). You can call it directly at any time:

```php
// Direct Telegram Bot API call:
$updatesResult = $bot->getUpdates(offset: 0, limit: 10);

if ($updatesResult->ok()) {
    foreach ($updatesResult->all() as $update) {
        echo "Update ID: {$update->updateId}\n";
    }
}
```

---

## Setting Running Mode via Config

You can also define the default running mode directly within the configuration:

```php
$config = Telegram::create('YOUR_BOT_TOKEN')
    ->withRunningMode(new WebhookMode(secretToken: 'my_secret'))
    ->build();

$bot = new Telegram($config);
$bot->run(MyHandler::class);
```

---

## ⚡ AutoMode: Adaptive Execution (CLI Polling & HTTP Webhook)

`AutoMode` is an intelligent, multi-vector execution strategy that eliminates the need to maintain separate entry points for CLI development and production webhooks. It dynamically detects whether the bot is running inside a command-line terminal (`PollingMode`) or responding to an HTTP web request (`WebhookMode`).

### Key Features of `AutoMode`:
1. **Multi-Vector Environment Detection:**
   - Detects incoming HTTP requests (`$_SERVER['REQUEST_METHOD'] === 'POST'`, Telegram secret headers) even within CLI-based web workers such as **RoadRunner**, **Swoole**, or **FrankenPHP**.
   - Detects standard terminal environments (`PHP_SAPI === 'cli'`) when no HTTP request is present.
2. **Telegram Conflict Prevention (`autoDeleteWebhook`):**
   - Automatically deletes any active webhook before starting `PollingMode` to prevent Telegram's notorious `409 Conflict: can't use getUpdates method while webhook is active`.
3. **Observability & Hooks:**
   - Emits structured logs to your configured PSR-3 logger detailing which mode was selected and why.
   - Provides an `onModeResolved(fn($mode, $type, $bot) => ...)` hook for custom lifecycle logic.
4. **Convenient Fluent API:**
   - Use `Telegram::create()->withAutoMode(...)` or `$bot->autoRun()`.

### Example 1: Fluent Builder Configuration

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;

$bot = Telegram::create($_ENV['TELEGRAM_BOT_TOKEN'])
    ->withPollingMode(new PollingMode(timeout: 45))
    ->withWebhookMode(new WebhookMode(secretToken: $_ENV['TELEGRAM_WEBHOOK_SECRET']))
    ->withAutoMode(autoDeleteWebhook: true) // Prevents 409 Conflict in local CLI
    ->client();

$bot->onCommand('start', function (Update $update, Telegram $bot) {
    $bot->sendMessage(text: 'Hello from AutoMode!');
});

// Single unified entry point:
// • In terminal (php bot.php): Runs continuous Long-Polling
// • Via Web Server (Nginx / Caddy / FrankenPHP): Handles incoming Webhook
$bot->run();
```

### Example 2: Quick `$bot->autoRun()` Execution

```php
use Tueen\Telegram\Telegram;

$bot = new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);

$bot->onCommand('ping', fn(Update $u, Telegram $b) => $b->sendMessage(text: 'pong!'));

// Automatically configures AutoMode and executes:
$bot->autoRun();
```

---

## 🔀 PHP 8.5 Pipe Operator (`|>`) Pipelines

PHP 8.5 introduces the native **Pipe Operator (`|>`)**, enabling functional, left-to-right expression composition. In traditional PHP, processing an incoming Telegram update often results in either deeply nested function calls:

```php
// Traditional nested approach (hard to read from inside out):
respond(dispatch(authenticate(filter(parseUpdate(file_get_contents('php://input'))))));
```

or excessive intermediate temporary variables:

```php
// Traditional intermediate variables approach:
$rawPayload = file_get_contents('php://input');
$update = $bot->parseUpdate($rawPayload);
$filtered = $guard->check($update);
$response = $router->dispatch($filtered);
```

With PHP 8.5 and `tueen/telegram`'s callable-friendly design, data flows naturally as a readable, linear pipeline.

---

### 1. Fundamental Syntax & First-Class Callables

The pipe operator passes the result of the left-hand expression as the first argument to the right-hand callable. You can combine it with first-class callables (`$bot->parseUpdate(...)`) and parenthesized closures `(fn($x) => ...)`:

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

$bot = new Telegram('YOUR_BOT_TOKEN');

// Linear left-to-right transformation:
$message = file_get_contents('php://input')
    |> $bot->parseUpdate(...)
    |> (fn(Update $update) => $update->findMessage())
    |> (fn($msg) => $msg?->findAnyText());
```

---

### 2. Production Webhook Ingestion Pipeline

In a webhook controller, you can pipe raw input directly from the HTTP stream into security validation, update parsing, flow handling, and routing without intermediate state:

```php
namespace App\Controllers;

use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Exceptions\TelegramException;

class TelegramWebhookController
{
    public function __invoke(Telegram $bot): void
    {
        file_get_contents('php://input')
            // Step 1: Parse JSON into a typed Update instance (#[\NoDiscard] safe)
            |> $bot->parseUpdate(...)
            // Step 2: Set current update on client for contextual auto-fill
            |> (function (Update $update) use ($bot): Update {
                $bot->setUpdate($update);
                return $update;
            })
            // Step 3: Prioritize active conversation flows
            |> (function (Update $update) use ($bot): ?Update {
                if ($bot->flowManager()->handle($update, $bot)) {
                    return null; // Flow consumed the update, halt pipeline
                }
                return $update;
            })
            // Step 4: Dispatch to attribute router if not consumed by a flow
            |> (function (?Update $update) use ($bot): void {
                if ($update !== null) {
                    $bot->router()->dispatch($update);
                }
            });
    }
}
```

---

### 3. Multi-Stage Filter & Guard Pipeline

Pipelines are particularly powerful for composing modular security guards, spam filters, and bot filters:

```php
use Tueen\Telegram\Types\Update;

// Dedicated pure or callable guard functions:
$rejectBannedUsers = function (Update $update): ?Update {
    $userId = $update->findUser()?->id;
    if ($userId !== null && in_array($userId, [/* banned user IDs */], true)) {
        return null; // Halt processing for banned users
    }
    return $update;
};

$rejectOldUpdates = function (?Update $update): ?Update {
    if ($update === null) return null;
    $date = $update->findMessage()?->date;
    // Drop updates older than 2 minutes:
    if ($date !== null && (time() - $date) > 120) {
        return null;
    }
    return $update;
};

$logAudit = function (?Update $update): ?Update {
    if ($update !== null) {
        error_log("Processing Update #{$update->updateId} for Chat {$update->findChat()?->id}");
    }
    return $update;
};

// Execute complete guard pipeline:
$payload
    |> $bot->parseUpdate(...)
    |> $rejectBannedUsers
    |> $rejectOldUpdates
    |> $logAudit
    |> (fn(?Update $update) => $update ? $bot->handleUpdate($update) : null);
```

---

### 4. Direct Action & Response Pipelines

You can transform an incoming update straight into an outbound API action using PHP 8.5 pipes:

```php
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Enums\ParseMode;

file_get_contents('php://input')
    |> $bot->parseUpdate(...)
    |> (fn(Update $update) => match (true) {
        $update->message?->text === '/ping' => $bot->sendMessage(
            chatId: $update->findChat()->id,
            text: '🏓 <b>Pong!</b>',
            parseMode: ParseMode::HTML
        ),
        $update->message?->text === '/help' => $bot->sendMessage(
            chatId: $update->findChat()->id,
            text: '💡 Send me any text to echo.'
        ),
        default => null,
    });
```

---

### 5. Architectural Benefits in Tueen

1. **Zero Intermediate State:** Eliminates redundant local variables (`$raw`, `$json`, `$obj`) that pollute scope and waste memory.
2. **Left-to-Right Readability:** Reading code mirrors the natural direction of data flow: from network stream, through domain filters, to Telegram API response.
3. **Callable & Hook Synergy:** Seamlessly integrates with Tueen's first-class callable methods (`$bot->parseUpdate(...)`, `$bot->sendMessage(...)`) and property hooks.
4. **Graceful Early Exits:** Steps can return `null` or specialized result types to short-circuit subsequent stages without deeply nested `if/else` checks.


