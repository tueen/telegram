# Keyboards & Interactive UI

Telegram bots provide two types of keyboards for interactive user experiences:
1. **Inline Keyboards (`InlineKeyboardMarkup`)**: Buttons attached directly underneath a message. Clicking them can trigger callback queries, open URLs, launch Telegram Web Apps, or switch inline queries.
2. **Reply Keyboards (`ReplyKeyboardMarkup`)**: Custom buttons that replace the user's regular keyboard on their phone/desktop.

---

## 1. Inline Keyboards (`InlineKeyboardMarkup`)

Inline keyboards are represented by the `InlineKeyboardMarkup` type or as structured arrays.

### Creating an Inline Keyboard

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InlineKeyboardButton;

$telegram = new Telegram('YOUR_BOT_TOKEN');

// Using structured PHP arrays:
$telegram->sendMessage(
    chatId: 12345678,
    text: "Please select an option:",
    replyMarkup: [
        'inline_keyboard' => [
            // Row 1
            [
                ['text' => '🌐 Visit Website', 'url' => 'https://tueen.dev'],
                ['text' => '⭐ Star on GitHub', 'url' => 'https://github.com/tueen/telegram'],
            ],
            // Row 2
            [
                ['text' => '👍 Like', 'callback_data' => 'action:like'],
                ['text' => '👎 Dislike', 'callback_data' => 'action:dislike'],
            ],
        ]
    ]
);
```

### Button Types & Features

Each button is an instance of `InlineKeyboardButton` (or an equivalent associative array). Telegram supports several button actions:

| Action | Key | Description | Example |
| :--- | :--- | :--- | :--- |
| **Callback Data** | `callback_data` | Sends an update to your bot when tapped (1-64 bytes). | `'callback_data' => 'buy:item_42'` |
| **Open URL** | `url` | Opens an HTTP/HTTPS or `tg://` link in browser/app. | `'url' => 'https://example.com'` |
| **Web App** | `web_app` | Launches a Telegram Mini App inside the client. | `'web_app' => ['url' => 'https://webapp.example.com']` |
| **Copy Text** | `copy_text` | Copies custom text directly to the user's clipboard. | `'copy_text' => ['text' => 'PROMO2026']` |
| **Switch Inline** | `switch_inline_query` | Prompts user to select a chat and inserts bot query. | `'switch_inline_query' => 'search query'` |
| **Telegram Pay** | `pay` | Pay button for invoice messages. | `'pay' => true` |

---

## 2. Handling Button Clicks (`CallbackQuery`)

When a user taps an inline button with `callback_data`, Telegram sends an `Update` with `callback_query`.

### Acknowledging Callbacks (`answerCallbackQuery`)

Telegram requires bots to answer every callback query within a few seconds (even with an empty response) to dismiss the client loading spinner:

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

$telegram->run(function (Update $update) use ($telegram) {
    $cb = $update->callbackQuery;
    if ($cb === null) {
        return;
    }

    // 1. Answer immediately to stop the loading icon:
    $telegram->answerCallbackQuery(
        callbackQueryId: $cb->id,
        text: "You clicked: {$cb->data}!",
        showAlert: false // true displays a native alert modal
    );

    // 2. Perform actions based on data:
    if ($cb->data === 'action:like') {
        // Edit the original message:
        $telegram->editMessageText(
            chatId: $cb->message?->chat->id,
            messageId: $cb->message?->messageId,
            text: "Thank you for liking this post! ❤️"
        );
    }
});
```

---

## 3. Reply Keyboards (`ReplyKeyboardMarkup`)

Reply keyboards present custom options above the message input field.

### Creating a Reply Keyboard

```php
use Tueen\Telegram\Types\ReplyKeyboardMarkup;

$telegram->sendMessage(
    chatId: 12345678,
    text: "Choose an action from the keyboard below:",
    replyMarkup: [
        'keyboard' => [
            [
                ['text' => '📞 Share Phone Number', 'request_contact' => true],
                ['text' => '📍 Share Location', 'request_location' => true],
            ],
            [
                ['text' => '📊 Create Poll', 'request_poll' => ['type' => 'regular']],
                ['text' => 'ℹ️ Help'],
            ]
        ],
        'resize_keyboard' => true,   // Automatically shrink buttons to fit screen
        'one_time_keyboard' => true, // Hide keyboard immediately after user taps an option
    ]
);
```

---

## 4. Removing Keyboards (`ReplyKeyboardRemove`)

To remove an active reply keyboard and restore the standard mobile/desktop text keyboard:

```php
$telegram->sendMessage(
    chatId: 12345678,
    text: "Keyboard removed.",
    replyMarkup: [
        'remove_keyboard' => true
    ]
);
```

---

## 5. Forcing Replies (`ForceReply`)

To instruct the Telegram app to automatically select the bot's message and prompt the user to reply:

```php
$telegram->sendMessage(
    chatId: 12345678,
    text: "What is your email address?",
    replyMarkup: [
        'force_reply' => true,
        'input_field_placeholder' => 'name@example.com'
    ]
);
```
