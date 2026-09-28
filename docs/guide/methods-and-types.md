# Methods & Types

`tueen/telegram` provides native, strictly typed support for Telegram Bot API methods and types with full IDE autocompletion and enums.

---

## Method Invocations

You can invoke API methods either dynamically or via dedicated Method classes:

### 1. Dynamic Method Calls (Recommended)

Call any Telegram method directly using camelCase and named arguments:

```php
use Tueen\Telegram\Enums\ParseMode;

$message = $bot->sendMessage(
    chatId: 12345678,
    text: "Hello from <b>Tueen</b>!",
    parseMode: ParseMode::HTML // Direct Enum instance
);
```

::: tip MANDATORY BEST PRACTICE: ALWAYS USE NAMED PARAMETERS
In `tueen/telegram`, **always invoke API methods using PHP named arguments** (`paramName: $value`).

```php
// ✅ RECOMMENDED: Clean, explicit, order-independent
$bot->sendMessage(
    text: "Welcome to the Royal Bot!",
    parseMode: ParseMode::HTML
);

// ❌ AVOID: Fragile positional arguments
$bot->sendMessage(123456, "Hello", null, null, null, null, 'HTML');
```

#### Why Named Parameters Are Essential:
1. **Contextual Auto-Injection:** Because parameters like `chatId`, `businessConnectionId`, `messageThreadId`, and query IDs are automatically inferred from the active update, named arguments allow you to supply only what you care about (e.g. `text: "..."`) without caring about parameter order.
2. **No Trailing Null Placeholders:** Telegram Bot API methods often accept 15 to 25+ parameters. Positional calls force you to pass dozens of `null` values just to set an option near the end.
3. **Forward-Compatibility:** When Telegram adds new parameters to Bot API methods in future updates, named arguments protect your code from signature changes or argument shifts.
4. **Self-Documenting & Clean:** Code is immediately readable and understandable in code reviews without needing to look up parameter order.
:::

Full IDE autocompletion and parameter docblocks are provided via the `@mixin TelegramMethods` contract.

### 2. Method Objects (For Custom Pipelines & Architecture)

Every Telegram Bot API method has a dedicated class in `Tueen\Telegram\Methods`:

```php
use Tueen\Telegram\Methods\SendMessage;

$method = new SendMessage(
    chatId: 12345678,
    text: "Hello from Tueen!"
);

$message = $bot->send($method);
```

### 3. Forward-Compatible Unknown / New Parameters

If Telegram introduces a new parameter before the library updates, you can pass it immediately as a named parameter or via variadic `$extra`:

```php
// If Telegram adds 'new_feature_flag' tomorrow:
$message = $bot->sendMessage(
    chatId: 12345678,
    text: "Testing new feature",
    newFeatureFlag: true // Seamlessly passed to the API
);
```

### 4. Passing Complex & Nested Types (Objects or Arrays)

Methods that accept complex structures (such as `reply_markup`, `link_preview_options`, or `reply_parameters`) accept both **strongly-typed Type objects** and **associative arrays**:

#### A. Using Strongly-Typed Type Objects
```php
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InlineKeyboardButton;
use Tueen\Telegram\Types\LinkPreviewOptions;

$bot->sendMessage(
    chatId: 123456,
    text: "Check out Tueen:",
    replyMarkup: new InlineKeyboardMarkup(
        inlineKeyboard: [
            [
                new InlineKeyboardButton(text: '🌐 Website', url: 'https://tueen.dev'),
                new InlineKeyboardButton(text: '⭐ GitHub', url: 'https://github.com/tueen/telegram'),
            ]
        ]
    ),
    linkPreviewOptions: new LinkPreviewOptions(isDisabled: true)
);
```

#### B. Using Structured Arrays
You can also pass arrays; the library automatically normalizes, converts nested objects/enums, and serializes them:
```php
$bot->sendMessage(
    chatId: 123456,
    text: "Choose an option:",
    replyMarkup: [
        'inline_keyboard' => [
            [
                ['text' => 'Option 1', 'callback_data' => 'opt_1'],
                ['text' => 'Option 2', 'callback_data' => 'opt_2'],
            ]
        ]
    ]
);
```

---

## 🎯 Contextual Default Parameter Injection

In bot applications, repetitive parameters like `chat_id`, `business_connection_id`, `message_thread_id`, or `user_id` are already present in the incoming `Update`. `tueen/telegram` features an **intelligent Context Resolver** that automatically extracts and injects these parameters whenever they are omitted or `null`.

### 1. Automatic `chat_id` Injection
Inside any update handler or flow step, you never have to specify `chat_id` manually:

```php
$bot->onCommand('start', function (Update $update, Telegram $bot) {
    // chatId is automatically resolved from $update!
    // Always use named parameters:
    $bot->sendMessage(text: "Welcome to the Royal Bot!");
    $bot->sendMessage(
        text: "How can I assist your Majesty today?",
        replyMarkup: $inlineKeyboard
    );
});
```

### 2. Supported Contextual Parameters
The following parameters are automatically inferred from the active update:

| Parameter | Automatically Inferred From |
| :--- | :--- |
| `chat_id` | Active chat from message, callback query, business message, channel post, or reaction |
| `business_connection_id` | Business connection or business message update |
| `message_thread_id` | Forum topic thread ID from the incoming message |
| `user_id` | Acting user ID (for `getUserProfilePhotos`, `getUserGifts`, etc.) |
| `message_id` | Primary message ID (for `deleteMessage`, `pinChatMessage`, etc.) |
| `inline_message_id` | Inline message ID from callback queries |
| `callback_query_id` | Query ID for `answerCallbackQuery(text: 'Done!')` |
| `inline_query_id` | Query ID for `answerInlineQuery(results: [...])` |
| `shipping_query_id` | Query ID for `answerShippingQuery(ok: true)` |
| `pre_checkout_query_id` | Query ID for `answerPreCheckoutQuery(ok: true)` |

### 3. Dual Resolution for Message Edits
Methods that can edit either a chat message or an inline query message (`editMessageText`, `editMessageCaption`, `editMessageReplyMarkup`, etc.) automatically detect whether the update came from an inline callback or a regular message:

```php
$bot->onCallbackQuery('confirm', function (Update $update, Telegram $bot) {
    // Automatically injects either inline_message_id OR (chat_id + message_id)
    $bot->editMessageText(text: "Confirmed!");
});
```

### 4. Custom Parameter Binding
You can bind custom default values or dynamic resolvers using `bindDefault`:

```php
// Always default parse_mode to HTML across all methods
$bot->bindDefault('parse_mode', fn(?Update $u, ?string $endpoint) => 'HTML');

// Send message without parse_mode parameter; HTML is injected automatically
$bot->sendMessage(text: "<b>Royal</b> Bot");
```

---

## Types & Property Access

All API responses are automatically deserialized into strongly-typed objects in `Tueen\Telegram\Types`.

### Dual Access: camelCase and snake_case

```php
// CamelCase property access
echo $message->messageId;
echo $message->chat->firstName;

// Snake_case property access
echo $message->message_id;

// ArrayAccess with snake_case
echo $message['message_id'];
echo $message['chat']['first_name'];
```

---

## Custom Result Wrapper Types

Methods that return primitive types (such as `boolean`, `integer`, or arrays) are wrapped in typed classes inside `Tueen\Telegram\Types\Custom\` so you can always check `$result->ok()`:

- **`BooleanResult`** (e.g. from `setWebhook`, `deleteMessage`)
  ```php
  $result = $bot->deleteMessage(chatId: 123, messageId: 456);
  if ($result->ok() && $result->value) {
      echo "Deleted successfully";
  }
  ```
- **`IntegerResult`** (e.g. from `getChatMemberCount`)
  ```php
  $count = $bot->getChatMemberCount(chatId: -100123);
  echo "Members: " . $count->value;
  ```
- **`StringResult`** (e.g. from `exportChatInviteLink`, `createInvoiceLink`)
  ```php
  $link = $bot->exportChatInviteLink(chatId: -100123);
  echo "Invite link: " . $link->value;
  ```
- **`ArrayResult<T>`** (e.g. from `getUpdates`, `forwardMessages`)
  ```php
  $updates = $bot->getUpdates();
  foreach ($updates as $update) {
    echo $update->updateId;
  }
  ```

---

## Forward Compatibility & Dynamic Fallback

If Telegram adds new fields or entire response types in future API versions, your application will **never crash**:

- The unknown field is preserved in `$extra` storage.
- You can access it immediately via `$message->newField` or `$message['new_field']`.
- If Telegram returns an entirely new Type object, it seamlessly falls back to the base `Type` class.
