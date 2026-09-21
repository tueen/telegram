# Update & Message Helpers

`tueen/telegram` takes full advantage of **PHP 8.4 Property Hooks** and smart helper traits to eliminate boilerplate null-checking and streamline bot development.

---

## 1. Update Types (`UpdateType`)

Telegram updates can represent 23+ different events (messages, button clicks, channel posts, reaction changes, etc.).

### Automatic Type Detection with Property Hooks
The `Update` object exposes a `$type` property powered by PHP 8.4 Property Hooks:

```php
use Tueen\Telegram\Enums\UpdateType;

// Check update type directly:
if ($update->type === UpdateType::MESSAGE) {
    // Normal user message
}

// Or use the fluent helper:
if ($update->isType(UpdateType::CALLBACK_QUERY)) {
    // Inline keyboard button click
}
```

### Smart Extractors on `Update`
Regardless of whether an update is a standard message, edited message, channel post, or callback query button click, smart extractors pull out the relevant models:

```php
// 1. Get the primary Message (from message, editedMessage, channelPost, callbackQuery->message, etc.)
$message = $update->getMessage();

// 2. Get the acting User (from message, callbackQuery, inlineQuery, myChatMember, etc.)
$user = $update->getUser();
echo "From: {$user?->firstName} (@{$user?->username})\n";

// 3. Get the destination Chat (from message, channelPost, callbackQuery, chatMember, etc.)
$chat = $update->getChat();
echo "Chat ID: {$chat?->id}\n";
```

---

## 2. Message Types (`MessageType`)

A `Message` in Telegram can contain various forms of media, system notifications, or text.

### Automatic Content Detection
The `Message` object detects its content type automatically:

```php
use Tueen\Telegram\Enums\MessageType;

// Direct property hook:
switch ($message->type) {
    case MessageType::TEXT:
        // Plain text or command
        break;
    case MessageType::PHOTO:
        // Photo attachment
        break;
    case MessageType::VOICE:
        // Voice note
        break;
    case MessageType::RICH_MESSAGE:
        // Formatted rich text block
        break;
    case MessageType::PINNED_MESSAGE:
        // A message was pinned
        break;
}

// Or via isType:
if ($message->isType(MessageType::STICKER)) {
    $stickerId = $message->sticker->fileId;
}
```

---

## 3. Bot Command Parsing

`Message` provides built-in command parsing utilities:

```php
// Checks if message starts with '/'
if ($message->isCommand()) {
    // Extract clean command name (strips slash and @botusername)
    // E.g. '/ban@my_bot 123 spam' -> 'ban'
    $command = $message->getCommand();

    // Extract command arguments as array of strings
    // E.g. ['123', 'spam']
    $args = $message->getArgs();

    switch ($command) {
        case 'start':
            $telegram->sendMessage(chatId: $message->chat->id, text: 'Welcome!');
            break;

        case 'ban':
            $targetUserId = (int)($args[0] ?? 0);
            $reason = $args[1] ?? 'None';
            // execute ban...
            break;
    }
}
```

### Getting Unified Text or Caption
Media messages (photos, videos, documents) in Telegram store their text under `caption`, while standard messages use `text`.

Use `$message->getText()` to retrieve whichever is present:

```php
// Returns $message->text ?? $message->caption ?? null
$text = $message->getText();
```
