# Running Modes (Webhook & Polling)

`tueen/telegram` features an elegant execution strategy separating how updates are received from how they are processed.

You can switch between **Webhook Mode** and **Long-Polling Mode** seamlessly. In all modes, incoming updates are resolved, stored in the `$telegram->update` property, and dispatched to your handler(s) with full type-safety.

---

## 🚀 Unified Execution with `$telegram->run()`

The `$telegram->run()` method is the central entry point for executing your bot. It inspects the configured `RunningMode` and dispatches incoming updates to your handlers.

### 1. Handler Signature
Handlers receive two parameters:
1. `Update $update` — The incoming update instance.
2. `Telegram $bot` — The `Telegram` client instance.

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

$telegram = new Telegram('YOUR_BOT_TOKEN');

$telegram->run(function (Update $update, Telegram $bot) {
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
$telegram->run(CommandHandler::class);
```

If a PSR-11 container (or callable resolver) is configured via `$telegram->setContainer($container)` or `ConfigBuilder::withContainer($container)`, Tueen will resolve handler dependencies through your container automatically.

### 3. Multiple Handlers & Middleware Chains
You can pass multiple handlers as variadic arguments or as an array. Handlers run in sequential order:

```php
$telegram->run(
    AuthMiddleware::class,
    CommandHandler::class,
    function (Update $update, Telegram $bot) {
        // Fallback logger
    }
);

// Or using an array:
$telegram->run([AuthMiddleware::class, CommandHandler::class]);
```

You can also pre-register handlers using the fluent `$telegram->handle(...)` method:

```php
$telegram
    ->handle(AuthMiddleware::class)
    ->handle(CommandHandler::class)
    ->run();
```

> [!TIP]
> If any handler explicitly returns `false`, execution of subsequent handlers in the chain will stop immediately for that update.

---

## 📦 The `$telegram->update` Property

The `Telegram` client maintains an internal `$update` property that defaults to `null`:

```php
private(set) ?Update $update = null;
```

- When `run()` executes under **Webhook Mode**, the incoming update is resolved from the HTTP request and assigned to `$telegram->update`.
- When `run()` executes under **Long-Polling Mode**, each received update is assigned to `$telegram->update` before being passed to handlers.

```php
// In a webhook controller:
$telegram->run();

// Access the resolved update directly:
$chatId = $telegram->update?->findChat()?->id;
```

---

## 1. Webhook Mode (`WebhookMode`)

In production environments (Nginx, Apache, Caddy, FrankenPHP, Laravel, Symfony), bots receive updates as incoming HTTP POST requests sent by Telegram.

### Basic Setup
```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Types\Update;

$telegram = new Telegram('YOUR_BOT_TOKEN');

// Configure Webhook mode with optional secret token
$telegram->setRunningMode(new WebhookMode(secretToken: 'your-secret-token'));

// Run and dispatch
$telegram->run(function (Update $update, Telegram $bot) {
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
$telegram->setRunningMode($mode);

// If the header is missing or doesn't match, a TelegramException is thrown immediately,
// protecting your server from forged requests.
$telegram->run(MyHandler::class);
```

### Fast Response (`safeResponse`)
Telegram expects your webhook endpoint to answer with an `HTTP 200 OK` within a few seconds. If your logic performs heavy tasks, call `safeResponse()` to immediately send HTTP 200 and detach the connection via `fastcgi_finish_request()`:

```php
$mode = new WebhookMode();
$mode->safeResponse(); // Sends HTTP 200, Content-Type: application/json, closes connection

// Continue heavy processing in the background:
// e.g. generate AI images, process database transactions, etc.
$telegram->run(HeavyProcessingHandler::class);
```

### Integration with Frameworks (Laravel, Symfony, PSR-7)
```php
// In a Laravel Controller:
public function webhook(Request $request, Telegram $telegram)
{
    $mode = new WebhookMode(
        secretToken: config('services.telegram.secret'),
        rawInput: $request->getContent(),
        headers: $request->headers->all()
    );

    $telegram->setRunningMode($mode);

    $telegram->run(function (Update $update, Telegram $bot) {
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

$telegram = new Telegram('YOUR_BOT_TOKEN');

// Set Polling mode
$polling = new PollingMode(
    timeout: 30,             // Long-polling timeout in seconds
    limit: 100,              // Updates per batch
    allowedUpdates: ['message', 'callback_query']
);

$telegram->setRunningMode($polling);

// Starts infinite worker loop in CLI:
echo "Bot started polling...\n";
$telegram->run(function (Update $update, Telegram $bot) {
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

$telegram->setRunningMode($polling);
$telegram->run(MyUpdateHandler::class);
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
2. **Resilience & Backoff:** Automatically handles transient network interruptions with a configurable cooldown (`errorBackoffSeconds`).
3. **Graceful Shutdown:** You can call `$polling->stop()` from a signal handler (e.g. `SIGINT` / `SIGTERM`) to cleanly exit the polling loop.

### Manual Generator Iteration
If you prefer standard PHP `foreach` iteration:

```php
foreach ($telegram->poll(timeout: 30) as $update) {
    // Process $update
}
```

---

## Native Telegram Bot API Method: `getUpdates`

The official Telegram Bot API method to fetch updates is `getUpdates` (plural). You can call it directly at any time:

```php
// Direct Telegram Bot API call:
$updatesResult = $telegram->getUpdates(offset: 0, limit: 10);

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

$telegram = new Telegram($config);
$telegram->run(MyHandler::class);
```

---

## PHP 8.5 Pipe Operator (`|>`) Pipelines

In modern PHP 8.5 applications, you can pipe raw update payloads directly into `$telegram->parseUpdate(...)` without intermediate variables:

```php
$response = file_get_contents('php://input')
    |> $telegram->parseUpdate(...)
    |> (function (Update $update) use ($telegram) {
        if ($update->message?->text === '/start') {
            return $telegram->sendMessage($update->message->chat->id, 'Welcome to Tueen!');
        }
        return null;
    });
```

