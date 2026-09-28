# Update Routing & Attributes

Instead of writing monolithic `switch` or `if/else` ladders inside your update handlers, `tueen/telegram` features an expressive, type-safe **Update Router**.

The router supports both **Fluent Route Definitions** and **Attribute-Driven Controllers** (via PHP 8 Attributes).

---

## 1. Fluent Routing

You can register routes directly on your `Telegram` client instance.

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

$telegram = new Telegram('YOUR_BOT_TOKEN');

// 1. Handle bot commands (/start, /help, etc.)
// Bot username (@my_bot) is stripped automatically:
$telegram->onCommand('start', function (Update $update, Telegram $bot) {
    $bot->sendMessage(
        chatId: $update->findChat()->id,
        text: 'Welcome! How can I assist you today?'
    );
});

// 2. Handle callback queries with parameterized patterns
$telegram->onCallbackQuery('item:{id}:details', function (Update $update, Telegram $bot, string $id) {
    $bot->answerCallbackQuery(callbackQueryId: $update->callbackQuery->id);
    $bot->sendMessage(
        chatId: $update->findChat()->id,
        text: "Viewing details for item #{$id}"
    );
});

// 3. Handle messages matching a regex pattern
$telegram->onMessage('/^contact support$/i', function (Update $update, Telegram $bot) {
    $bot->sendMessage(
        chatId: $update->findChat()->id,
        text: 'A support agent will reach out shortly.'
    );
});

// 4. Handle inline queries
$telegram->onInlineQuery(function (Update $update, Telegram $bot) {
    $query = $update->inlineQuery->query;
    // Answer inline query...
});

// 5. Catch-all fallback route
$telegram->onFallback(function (Update $update, Telegram $bot) {
    $bot->sendMessage(
        chatId: $update->findChat()?->id,
        text: 'Sorry, I did not understand that command.'
    );
});

// Execute the bot (automatically triggers router dispatch)
$telegram->run();
```

---

## 2. Parameter Extraction in Routes

The router provides first-class support for parameterized patterns using `{paramName}`:

```php
$telegram->onCallbackQuery('cart:add:{productId}:{quantity}', function (
    Update $update,
    Telegram $bot,
    string $productId,
    string $quantity
) {
    // Parameters are automatically injected into the handler arguments!
});
```

You can also use full regular expressions:

```php
$telegram->onCallbackQuery('/^order_(?P<action>approve|reject)_(?P<id>\d+)$/', function (
    Update $update,
    Telegram $bot,
    string $action,
    string $id
) {
    // Named regex capture groups are passed as parameters
});
```

---

## 3. Attribute-Driven Controllers

For medium to large applications, you can organize your routes into dedicated Controller classes decorated with PHP 8 Attributes:

### Example Controller

```php
namespace App\Controllers;

use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Routing\Attributes\OnCallbackQuery;
use Tueen\Telegram\Routing\Attributes\OnMessage;
use Tueen\Telegram\Routing\Attributes\OnInlineQuery;

class ShopController
{
    #[OnCommand('shop')]
    public function openShop(Update $update, Telegram $bot): void
    {
        $bot->sendMessage(
            chatId: $update->findChat()->id,
            text: 'Welcome to our shop!'
        );
    }

    #[OnCallbackQuery('product:{id}')]
    public function showProduct(Update $update, Telegram $bot, string $id): void
    {
        $bot->answerCallbackQuery($update->callbackQuery->id);
        $bot->sendMessage(
            chatId: $update->findChat()->id,
            text: "Loading product #{$id}..."
        );
    }

    #[OnMessage('/^\$([0-9\.]+)$/')]
    public function handleTip(Update $update, Telegram $bot, string $amount): void
    {
        $bot->sendMessage(
            chatId: $update->findChat()->id,
            text: "Received tip: \${$amount}"
        );
    }

    #[OnInlineQuery]
    public function search(Update $update, Telegram $bot): void
    {
        // Handle inline query
    }
}
```

### Registering Controllers

Register controllers with `$telegram->registerController(...)`:

```php
$telegram->registerController(ShopController::class);
$telegram->registerController(UserController::class);

$telegram->run();
```

If a PSR-11 container is configured (`$telegram->setContainer($container)`), controller instances and their constructor dependencies will be resolved automatically.

---

## 4. Available Routing Attributes

| Attribute | Matches | Example |
| :--- | :--- | :--- |
| `#[OnCommand('name')]` | Bot commands (`/name` or `/name@bot`) | `#[OnCommand('help')]` |
| `#[OnCallbackQuery('pattern')]` | Callback queries matching pattern or regex | `#[OnCallbackQuery('confirm:{id}')]` |
| `#[OnMessage('pattern')]` | Text messages matching string or regex | `#[OnMessage('/hi\|hello/i')]` |
| `#[OnInlineQuery('pattern')]` | Inline queries matching optional pattern | `#[OnInlineQuery]` |
| `#[OnUpdate('type')]` | Specific update types (`channel_post`, etc.) | `#[OnUpdate('edited_message')]` |
