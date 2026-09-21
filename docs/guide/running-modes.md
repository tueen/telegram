# Running Modes (Webhook & Polling)

`tueen/telegram` features an execution strategy inspired by Nutgram, separating how updates are received from how they are processed.

You can switch between **Webhook Mode** and **Long-Polling Mode** seamlessly.

---

## 1. Webhook Mode (`WebhookMode`)

In production environments (Nginx, Apache, Caddy, FrankenPHP, Laravel, Symfony), bots typically receive updates as incoming HTTP POST requests sent by Telegram.

### Basic Setup
```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Types\Update;

$telegram = new Telegram('YOUR_BOT_TOKEN');

// 1. Configure Webhook mode
$telegram->setRunningMode(new WebhookMode(secretToken: 'your-secret-token'));

// 2. Process incoming update
$telegram->run(function (Update $update) use ($telegram) {
    if ($update->message) {
        $telegram->sendMessage(
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
$update = $telegram->getUpdate();
```

### Fast Response (`safeResponse`)
Telegram expects your webhook endpoint to answer with an `HTTP 200 OK` within a few seconds. If your logic performs heavy tasks, call `safeResponse()` to immediately send HTTP 200 and detach the connection via `fastcgi_finish_request()`:

```php
$mode = new WebhookMode();
$mode->safeResponse(); // Sends HTTP 200, Content-Type: application/json, closes connection

// Continue heavy processing in the background:
// e.g. generate AI images, process database transactions, etc.
```

### Integration with Frameworks (Laravel, Symfony, PSR-7)
If you are using Laravel, RoadRunner, Swoole, or ReactPHP where `php://input` is not accessed directly:

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

    $telegram->run(function (Update $update) use ($telegram) {
        // Handle update
    });

    return response()->json(['ok' => true]);
}
```

---

## 2. Long-Polling Mode (`PollingMode`)

During local development or for CLI background workers, Long-Polling continuously asks Telegram for new updates.

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
$telegram->run(function (Update $update) use ($telegram) {
    echo "Received update #{$update->updateId}\n";

    if ($update->message?->text === '/ping') {
        $telegram->sendMessage(
            chatId: $update->message->chat->id,
            text: 'Pong!'
        );
    }
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

## Setting Running Mode via Config

You can also define the default running mode directly within the configuration:

```php
$config = Telegram::create('YOUR_BOT_TOKEN')
    ->withRunningMode(new WebhookMode(secretToken: 'my_secret'))
    ->build();

$telegram = new Telegram($config);
```
