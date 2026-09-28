# Update & Message Helpers

`tueen/telegram` provides helper methods and computed properties on `Update`, `Message`, `Chat`, and `User` objects to simplify event processing and eliminate boilerplate null checks.

---

## 1. Update Helpers (`Update`)

### Checking Update Types (`isType`)
The `Update` object exposes a `$type` property and a variadic `isType` method that accepts multiple enums or string names:

```php
use Tueen\Telegram\Enums\UpdateType;

// Check single type:
if ($update->type === UpdateType::MESSAGE) {
    // Normal user message
}

// Check against multiple types at once:
if ($update->isType(UpdateType::MESSAGE, UpdateType::EDITED_MESSAGE)) {
    // Message or edited message
}

// Check by string values:
if ($update->isType('callback_query', 'message')) {
    // Matches callback_query or message
}
```

### Smart In-Memory Model Finders
Unlike direct properties (such as `$update->message` which is only set on standard messages), smart finders search across all branches of the incoming update payload (including edited messages, channel posts, callback queries, and business messages):

```php
// 1. Find the primary Message:
$message = $update->findMessage();

// 2. Find the acting User:
$user = $update->findUser();
echo "User: {$user?->fullName} (@{$user?->username})\n";

// 3. Find the target Chat:
$chat = $update->findChat();
echo "Chat: {$chat?->fullName} (ID: {$chat?->id})\n";
```
*(Note: `getMessage()`, `getUser()`, and `getChat()` are also supported as backward-compatible aliases).*

### ID & File Shortcut Finders
Quickly extract IDs directly from the update without chaining null-safe calls:

```php
$userId    = $update->findUserId();    // Returns ?int
$chatId    = $update->findChatId();    // Returns ?int
$messageId = $update->findMessageId(); // Returns ?int
$fileId    = $update->findFileId();    // Returns ?string (or $update->fileId)
$bizConnId = $update->findBusinessConnectionId(); // Returns ?string
$threadId  = $update->findMessageThreadId();      // Returns ?int
```

### Modern PHP 8.4 Property Hooks
Using PHP 8.4 property hooks, you can access these values directly as properties without invoking methods:

```php
$chatId    = $update->chatId;
$userId    = $update->userId;
$messageId = $update->messageId;
$bizId     = $update->businessConnectionId;
$threadId  = $update->messageThreadId;
$cbQueryId = $update->callbackQueryId;
$inlineId  = $update->inlineMessageId;
```

---

## 2. Client-Level Contextual Shortcuts (`Telegram`)

The `Telegram` client facade exposes direct helper methods that proxy the active update:

```php
// Active ID getters
$chatId = $bot->chatId();
$userId = $bot->userId();
$msgId  = $bot->messageId();
$bizId  = $bot->businessConnectionId();

// Model objects
$user    = $bot->user();    // ?User
$chat    = $bot->chat();    // ?Chat
$message = $bot->message(); // ?Message
```

---

## 2. Message Helpers (`Message`)

### Checking Message Types (`isType` & `isMessage`)
Check message contents using property hooks or the variadic `isType` / `isMessage` helpers:

```php
use Tueen\Telegram\Enums\MessageType;

// Direct property:
if ($message->type === MessageType::TEXT) {
    // Plain text
}

// Variadic check for multiple message types:
if ($message->isMessage(MessageType::TEXT, MessageType::PHOTO, MessageType::DOCUMENT)) {
    // Message has text, photo, or document
}

// Check by string names:
if ($message->isType('photo', 'video')) {
    // Media message
}
```

### Checking Replies (`isRepliedToMessage`)
Determine whether a message is a reply to another message, or specifically a reply to a particular message ID or object:

```php
// Check if message is a reply to any message:
if ($message->isRepliedToMessage()) {
    echo "This is a reply to message #{$message->replyToMessage?->messageId}\n";
}

// Check if message is a reply to a specific message ID:
if ($message->isRepliedToMessage(12345)) {
    echo "User replied specifically to message #12345\n";
}

// Check if message is a reply to a specific Message object:
if ($message->isRepliedToMessage($promptMessage)) {
    echo "User replied to the prompt message\n";
}
```

### Extracting Any Text, Caption, or Rich Text (`findAnyText`)
Text content in Telegram can reside in several places:
- Standard messages use `text`
- Media messages (photos, videos, documents) use `caption`
- Formatted rich messages use `rich_message` with structured blocks

Use `$message->findAnyText()` (or `$message->findText()`) to retrieve whatever text content is present:

```php
// Returns text, caption, or recursively extracted text from rich message blocks (or null)
$text = $message->findAnyText();
```
*(Note: `$message->getText()` is also supported as a backward-compatible alias).*

### Media & File ID Extraction (`findFileId` & `findLargestPhoto`)
Telegram media messages attach files across different properties (`photo`, `video`, `document`, `audio`, `voice`, `animation`, `sticker`, `paidMedia`, etc.).

Use `$message->findFileId()` (or `$message->fileId`) to immediately obtain the active `file_id`:

```php
// Automatically inspects photo, video, document, audio, voice, animation, sticker, etc.
$fileId = $message->findFileId(); // or $message->fileId

// For photos with multiple resolutions, findFileId() automatically resolves the highest resolution:
if ($message->isType(MessageType::PHOTO)) {
    $largeFileId = $message->findFileId();
    
    // You can also retrieve the largest PhotoSize object directly:
    $largestPhoto = $message->findLargestPhoto();
    echo "Dimensions: {$largestPhoto->width}x{$largestPhoto->height}\n";
}
```

### Bot Command Parsing
Extract commands and their arguments with built-in parsers:

```php
if ($message->isCommand()) {
    // Extract clean command name (strips slash and @botusername)
    // E.g. '/ban@my_bot 123 spam' -> 'ban'
    $command = $message->getCommand();

    // Extract command arguments as array of strings
    // E.g. ['123', 'spam']
    $args = $message->getArgs();

    switch ($command) {
        case 'start':
            $bot->sendMessage(chatId: $message->chat->id, text: 'Welcome!');
            break;

        case 'ban':
            $targetUserId = (int)($args[0] ?? 0);
            $reason = $args[1] ?? 'None';
            // execute ban...
            break;
    }
}
```

---

## 3. Chat & User Helpers

### Automatic `fullName`
`Chat` and `User` models provide an automatic `$fullName` property:

```php
// On Chat: Returns title for groups/channels, or "first_name last_name" for private chats
echo $chat->fullName;

// On User: Returns "first_name last_name" (or just first_name)
echo $user->fullName;
```
