# Update & Message Helpers

`tueen/telegram` provides helper methods and computed properties on `Update` and `Message` objects to simplify handling incoming events and eliminate boilerplate null checks.

---

## 1. Update Types (`UpdateType`)

Telegram updates can represent various events, such as messages, callback queries, channel posts, or reaction changes.

### Automatic Type Detection
The `Update` object exposes a computed `$type` property:

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

### Smart In-Memory Finders on `Update`
Unlike a direct property (such as `$update->message` which is only present on standard messages), smart finder methods search across all branches of the incoming update payload (including edited messages, channel posts, callback queries, and business messages):

```php
// 1. Find the primary Message (from message, editedMessage, channelPost, callbackQuery->message, etc.)
$message = $update->findMessage();

// 2. Find the acting User (from message, callbackQuery, inlineQuery, myChatMember, etc.)
$user = $update->findUser();
echo "From: {$user?->firstName} (@{$user?->username})\n";

// 3. Find the destination Chat (from message, channelPost, callbackQuery, chatMember, etc.)
$chat = $update->findChat();
echo "Chat ID: {$chat?->id}\n";
```
*(Note: `getMessage()`, `getUser()`, and `getChat()` are also supported as backward-compatible aliases).*

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

### Extracting Any Text, Caption, or Rich Text (`findAnyText`)
In Telegram, text content can appear across different fields:
- Standard text messages use `text`
- Media messages (photos, videos, documents) use `caption`
- Rich formatted messages use `rich_message` with structured blocks

Use `$message->findAnyText()` (or its alias `$message->findText()`) to retrieve whatever text content exists:

```php
// Returns text, caption, or extracted text from rich message blocks (or null)
$text = $message->findAnyText();
```
*(Note: `$message->getText()` is also supported as a backward-compatible alias).*
