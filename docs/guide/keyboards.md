# Fluent Keyboard Builders

`tueen/telegram` provides modern, fluent builders for creating both **Inline Keyboards** (`InlineKeyboardMarkup`) and **Reply Keyboards** (`ReplyKeyboardMarkup` / `ReplyKeyboardRemove`).

---

## 1. Inline Keyboards (`InlineKeyboard`)

Inline keyboards appear directly attached to a specific message. Buttons trigger actions such as callback queries, opening URLs, launching Telegram Web Apps, or initiating payments.

### Basic Usage

Use `InlineKeyboard::make()` followed by rows and buttons:

```php
use Tueen\Telegram\Keyboards\InlineKeyboard;

$keyboard = InlineKeyboard::make()
    ->row()
        ->callback('👍 Like', 'action:like')
        ->callback('👎 Dislike', 'action:dislike')
    ->row()
        ->url('🌐 Visit Website', 'https://tueen.org')
    ->build();

$bot->sendMessage(
    chatId: $chatId,
    text: 'Please cast your vote:',
    replyMarkup: $keyboard
);
```

### Supported Button Types

`InlineKeyboard` supports all Telegram Bot API 10.3 inline button types:

```php
$keyboard = InlineKeyboard::make()
    // Callback data button
    ->callback('Confirm', 'order:confirm')

    // External URL button
    ->url('Documentation', 'https://tueen.org/docs')

    // Telegram Mini App (Web App)
    ->webApp('Open App', 'https://webapp.tueen.org')

    // Seamless Telegram Login URL
    ->loginUrl('Login with Telegram', 'https://example.com/auth/telegram')

    // Copy text to clipboard on tap (Bot API 7.5+)
    ->copyText('Copy Promo Code', 'TUEEN-VIP-2026')

    // Telegram Payments Pay button
    ->pay('💳 Pay Now')

    // Prompt user to select a chat and paste inline query
    ->switchInlineQuery('Share with friends', 'check out this bot!')

    // Insert inline query into current chat
    ->switchInlineQueryCurrentChat('Search here', 'query')

    // Prompt user to select a specific type of chat (channel, group, etc.)
    ->switchInlineQueryChosenChat('Send to Channel', $chosenChatConfig)

    ->build();
```

### Auto-Chunking Buttons (`chunk`)

Instead of manually breaking rows with `->row()`, you can add all buttons and let `chunk($size)` automatically arrange them into neat rows:

```php
$builder = InlineKeyboard::make();

for ($i = 1; $i <= 9; $i++) {
    $builder->callback((string) $i, "num:{$i}");
}

// Automatically divides into 3 rows of 3 buttons:
$grid = $builder->chunk(3)->build();
```

---

## 2. Reply Keyboards (`ReplyKeyboard`)

Reply keyboards replace the user's regular keyboard with custom option buttons.

### Basic Usage

```php
use Tueen\Telegram\Keyboards\ReplyKeyboard;

$keyboard = ReplyKeyboard::make()
    ->resize() // Automatically fit buttons neatly
    ->placeholder('Select an option...')
    ->row()
        ->text('📦 My Orders')
        ->text('⚙️ Settings')
    ->row()
        ->requestContact('📱 Share Phone Number')
        ->requestLocation('📍 Share Location')
    ->build();

$bot->sendMessage(
    chatId: $chatId,
    text: 'Main Menu:',
    replyMarkup: $keyboard
);
```

### Supported Features & Button Types

```php
$keyboard = ReplyKeyboard::make()
    // Keyboard behaviors:
    ->resize(true)                  // Scale keyboard vertically to button count
    ->oneTime(true)                 // Hide keyboard after first button press
    ->persistent(true)              // Keep keyboard visible even when soft keyboard is closed
    ->selective(true)               // Show only to specific target users (e.g. in groups)
    ->placeholder('Type message...')// Placeholder text in input bar

    // Button types:
    ->text('Plain Text')
    ->requestContact('Send Contact')
    ->requestLocation('Send Location')
    ->requestPoll('Create a Quiz', type: 'quiz')
    ->requestUsers('Select Friends', requestId: 1, userIsBot: false, maxQuantity: 5)
    ->requestChat('Select Channel', requestId: 2, chatIsChannel: true)
    ->webApp('Open Mini App', 'https://webapp.tueen.org')

    ->build();
```

### Removing Keyboards (`ReplyKeyboard::remove`)

To hide an active reply keyboard from the user's interface:

```php
$bot->sendMessage(
    chatId: $chatId,
    text: 'Keyboard dismissed.',
    replyMarkup: ReplyKeyboard::remove()
);
```
