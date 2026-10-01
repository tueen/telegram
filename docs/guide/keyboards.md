# Fluent Keyboard Builders

`tueen/telegram` provides modern, fluent builders for creating both **Inline Keyboards** (`InlineKeyboardMarkup`) and **Reply Keyboards** (`ReplyKeyboardMarkup` / `ReplyKeyboardRemove`).

---

## 🔘 1. Inline Keyboards (`InlineKeyboard`)

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

`InlineKeyboard` supports all Telegram Bot API inline button types:

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

    // Copy text to clipboard on tap
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

## ⌨️ 2. Reply Keyboards (`ReplyKeyboard`)

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

### Dismissing Keyboards (`ReplyKeyboard::remove`)

To hide an active reply keyboard from the user's interface:

```php
$bot->sendMessage(
    chatId: $chatId,
    text: 'Keyboard dismissed.',
    replyMarkup: ReplyKeyboard::remove()
);
```

---

## 🧭 3. Keyboard Builders API Reference

Below is the complete reference of methods available on `InlineKeyboard` and `ReplyKeyboard`.

### 🔘 `InlineKeyboard` Builder (`Tueen\Telegram\Keyboards\InlineKeyboard`)

<ApiGroup description="Fluent builder for constructing Telegram InlineKeyboardMarkup payloads.">
  <ApiCard
    sig="InlineKeyboard::make(): self"
    returns="InlineKeyboard"
    badge="Factory"
    desc="Initializes a new inline keyboard builder instance."
  />
  <ApiCard
    sig="row(): self"
    returns="self"
    badge="Layout"
    desc="Starts a new button row in the inline keyboard."
  />
  <ApiCard
    sig="callback(string $text, string $callbackData): self"
    returns="self"
    badge="Button"
    desc="Appends an inline button that sends callback_data back to the bot when clicked."
  />
  <ApiCard
    sig="url(string $text, string $url): self"
    returns="self"
    badge="Button"
    desc="Appends an inline button that opens an external HTTP/HTTPS URL in the user's browser."
  />
  <ApiCard
    sig="webApp(string $text, string $url): self"
    returns="self"
    badge="Button"
    desc="Appends an inline button that launches a Telegram Mini App (Web App) modal."
  />
  <ApiCard
    sig="loginUrl(string $text, string|LoginUrl $loginUrl): self"
    returns="self"
    badge="Button"
    desc="Appends an inline button that authorizes the user via Telegram Login Widget."
  />
  <ApiCard
    sig="copyText(string $text, string $copyText): self"
    returns="self"
    badge="Button"
    desc="Appends an inline button that copies the specified text to clipboard on tap."
  />
  <ApiCard
    sig="pay(string $text): self"
    returns="self"
    badge="Button"
    desc="Appends a Telegram Payments invoice payment button (must be the first button in first row)."
  />
  <ApiCard
    sig="switchInlineQuery(string $text, string $query = ''): self"
    returns="self"
    badge="Button"
    desc="Prompts user to select a chat and inserts '@bot query' into the chat input bar."
  />
  <ApiCard
    sig="switchInlineQueryCurrentChat(string $text, string $query = ''): self"
    returns="self"
    badge="Button"
    desc="Inserts '@bot query' directly into the current chat's input bar."
  />
  <ApiCard
    sig="chunk(int $size): self"
    returns="self"
    badge="Layout"
    desc="Automatically groups all queued buttons into uniform rows of the specified size."
  />
  <ApiCard
    sig="build(): InlineKeyboardMarkup"
    returns="InlineKeyboardMarkup"
    badge="Terminal"
    desc="Compiles and returns the finalized InlineKeyboardMarkup type instance."
  />
</ApiGroup>

---

### ⌨️ `ReplyKeyboard` Builder (`Tueen\Telegram\Keyboards\ReplyKeyboard`)

<ApiGroup description="Fluent builder for constructing Telegram ReplyKeyboardMarkup and ReplyKeyboardRemove payloads.">
  <ApiCard
    sig="ReplyKeyboard::make(): self"
    returns="ReplyKeyboard"
    badge="Factory"
    desc="Initializes a new reply keyboard builder instance."
  />
  <ApiCard
    sig="row(): self"
    returns="self"
    badge="Layout"
    desc="Starts a new button row in the reply keyboard."
  />
  <ApiCard
    sig="text(string $text): self"
    returns="self"
    badge="Button"
    desc="Appends a regular text button that sends the button text as a user message."
  />
  <ApiCard
    sig="requestContact(string $text): self"
    returns="self"
    badge="Button"
    desc="Appends a button prompting the user to share their verified phone number."
  />
  <ApiCard
    sig="requestLocation(string $text): self"
    returns="self"
    badge="Button"
    desc="Appends a button prompting the user to share their current GPS location."
  />
  <ApiCard
    sig="requestPoll(string $text, ?string $type = null): self"
    returns="self"
    badge="Button"
    desc="Appends a button allowing the user to create and send a regular poll or quiz."
  />
  <ApiCard
    sig="resize(bool $resize = true): self"
    returns="self"
    badge="Modifier"
    desc="Scales the reply keyboard vertically to fit the button rows neatly."
  />
  <ApiCard
    sig="oneTime(bool $oneTime = true): self"
    returns="self"
    badge="Modifier"
    desc="Hides the reply keyboard automatically as soon as any button is tapped."
  />
  <ApiCard
    sig="persistent(bool $persistent = true): self"
    returns="self"
    badge="Modifier"
    desc="Keeps the reply keyboard visible even when the regular software keyboard is opened or closed."
  />
  <ApiCard
    sig="placeholder(string $placeholder): self"
    returns="self"
    badge="Modifier"
    desc="Sets custom placeholder text inside the chat input bar when this keyboard is active."
  />
  <ApiCard
    sig="build(): ReplyKeyboardMarkup"
    returns="ReplyKeyboardMarkup"
    badge="Terminal"
    desc="Compiles and returns the finalized ReplyKeyboardMarkup type instance."
  />
  <ApiCard
    sig="ReplyKeyboard::remove(bool $selective = false): ReplyKeyboardRemove"
    returns="ReplyKeyboardRemove"
    badge="Removal"
    desc="Creates a ReplyKeyboardRemove instance to dismiss active reply keyboards."
  />
</ApiGroup>
