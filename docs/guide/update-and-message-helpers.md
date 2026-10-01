# Update & Message Helpers

`tueen/telegram` provides helper methods and computed properties on `Update`, `Message`, `Chat`, and `User` objects to simplify event processing and eliminate boilerplate null checks.

---

## ⚡ 1. Update Helpers (`Update`)

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

## 🌐 2. Client-Level Contextual Shortcuts (`Telegram` & `Context`)

The `Telegram` client facade and `Context` instance expose modern PHP 8.4 Property Hooks that proxy the active update without empty parentheses:

```php
// Active Contextual ID Properties
$chatId = $bot->chatId;
$userId = $bot->userId;
$msgId  = $bot->messageId;
$bizId  = $bot->businessConnectionId;

// Model objects
$user    = $bot->user;    // ?User
$chat    = $bot->chat;    // ?Chat
$message = $bot->message; // ?Message
```

---

## 💬 3. Message Helpers (`Message`)

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
    $largestPhoto = $message->findLargestPhoto(); // or $message->largestPhoto
    echo "Dimensions: {$largestPhoto->width}x{$largestPhoto->height}\n";
}
```

### Bot Command Parsing
Extract commands and their arguments with built-in property hooks:

```php
if ($message->isCommand()) {
    // Extract clean command name (strips slash and @botusername) via property hook
    // E.g. '/ban@my_bot 123 spam' -> 'ban'
    $command = $message->command;

    // Extract command arguments as array of strings via property hook
    // E.g. ['123', 'spam']
    $args = $message->args;

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

## 👤 4. Chat & User Helpers

### Automatic `fullName`
`Chat` and `User` models provide an automatic `$fullName` property:

```php
// On Chat: Returns title for groups/channels, or "first_name last_name" for private chats
echo $chat->fullName;

// On User: Returns "first_name last_name" (or just first_name)
echo $user->fullName;
```

---

## 🧭 5. Complete Helpers API Catalog

Below is the complete reference of all computed property hooks and helper methods provided by the `HasUpdateHelpers`, `HasMessageHelpers`, `HasChatHelpers`, and `HasUserHelpers` traits.

### ⚡ `Update` Helpers (`HasUpdateHelpers`)

<ApiGroup description="Computed property hooks and multi-branch finders available on every Update instance.">
  <ApiCard
    type="property"
    sig="public ?UpdateType $type"
    returns="?UpdateType"
    badge="Property Hook"
    desc="Active update type classified into an UpdateType backed enum."
  />
  <ApiCard
    type="property"
    sig="public ?int $chatId"
    returns="?int"
    badge="Property Hook"
    desc="Resolved Telegram Chat ID extracted across all update payload branches."
  />
  <ApiCard
    type="property"
    sig="public ?int $userId"
    returns="?int"
    badge="Property Hook"
    desc="Resolved acting Telegram User ID extracted across all update payload branches."
  />
  <ApiCard
    type="property"
    sig="public ?int $messageId"
    returns="?int"
    badge="Property Hook"
    desc="Resolved primary message ID extracted across all update payload branches."
  />
  <ApiCard
    type="property"
    sig="public ?string $fileId"
    returns="?string"
    badge="Property Hook"
    desc="Resolved media file ID attached to incoming message or callback payload."
  />
  <ApiCard
    type="property"
    sig="public ?string $businessConnectionId"
    returns="?string"
    badge="Property Hook"
    desc="Resolved business connection identifier for Telegram Business updates."
  />
  <ApiCard
    type="property"
    sig="public ?int $messageThreadId"
    returns="?int"
    badge="Property Hook"
    desc="Resolved forum topic thread ID for supergroup topics."
  />
  <ApiCard
    sig="isType(UpdateType|string ...$types): bool"
    returns="bool"
    badge="Helper"
    desc="Checks whether the update matches any of the specified UpdateType enums or string names."
  />
  <ApiCard
    sig="findMessage(): ?Message"
    returns="?Message"
    badge="Smart Finder"
    desc="Inspects all update branches (message, edited_message, channel_post, callback_query->message, business_message) to find the active Message."
  />
  <ApiCard
    sig="findUser(): ?User"
    returns="?User"
    badge="Smart Finder"
    desc="Finds the acting User across messages, callbacks, inline queries, chat members, or reactions."
  />
  <ApiCard
    sig="findChat(): ?Chat"
    returns="?Chat"
    badge="Smart Finder"
    desc="Finds the target Chat across messages, channel posts, callbacks, chat join requests, or boosts."
  />
</ApiGroup>

---

### 💬 `Message` Helpers (`HasMessageHelpers`)

<ApiGroup description="Computed property hooks and payload inspection methods on Message instances.">
  <ApiCard
    type="property"
    sig="public ?MessageType $type"
    returns="?MessageType"
    badge="Property Hook"
    desc="Classifies message payload into a MessageType backed enum (TEXT, PHOTO, VIDEO, DOCUMENT, etc.)."
  />
  <ApiCard
    type="property"
    sig="public bool $isCommand"
    returns="bool"
    badge="Property Hook"
    desc="Returns true if the message begins with a Telegram bot command (/command)."
  />
  <ApiCard
    type="property"
    sig="public ?string $command"
    returns="?string"
    badge="Property Hook"
    desc="Extracted bot command name, stripped of leading slash and @botusername suffix (e.g. 'start')."
  />
  <ApiCard
    type="property"
    sig="public array $args"
    returns="array<int, string>"
    badge="Property Hook"
    desc="Extracted command arguments split by whitespace into a clean list of strings."
  />
  <ApiCard
    type="property"
    sig="public ?string $textOrCaption"
    returns="?string"
    badge="Property Hook"
    desc="Normalized text content: returns message text, media caption, or recursively extracted text from rich blocks."
  />
  <ApiCard
    type="property"
    sig="public ?string $fileId"
    returns="?string"
    badge="Property Hook"
    desc="Highest-resolution file ID extracted from photo, video, document, audio, voice, sticker, animation, or video note."
  />
  <ApiCard
    type="property"
    sig="public ?PhotoSize $largestPhoto"
    returns="?PhotoSize"
    badge="Property Hook"
    desc="Returns the highest-resolution PhotoSize instance if the message contains a photo array."
  />
  <ApiCard
    sig="isType(MessageType|string ...$types): bool"
    returns="bool"
    badge="Helper"
    desc="Checks whether the message content matches any of the given MessageType enums or string names."
  />
  <ApiCard
    sig="isRepliedToMessage(int|Message|null $target = null): bool"
    returns="bool"
    badge="Helper"
    desc="Checks whether the message is a reply to any message, or matches a specific target message ID or Message instance."
  />
  <ApiCard
    sig="findAnyText(): ?string"
    returns="?string"
    badge="Helper"
    desc="Recursively extracts text from text, caption, or rich message structured blocks."
  />
</ApiGroup>

---

### 👤 `Chat` & `User` Helpers (`HasChatHelpers` & `HasUserHelpers`)

<ApiGroup description="Computed identity and chat type property hooks on Chat and User objects.">
  <ApiCard
    type="property"
    sig="public string $fullName"
    returns="string"
    badge="Property Hook"
    desc="On User: 'First Last' or 'First'. On Chat: chat title for groups/channels, or user full name for private chats."
  />
  <ApiCard
    type="property"
    sig="public bool $isPrivate"
    returns="bool"
    badge="Property Hook"
    desc="Returns true if chat type is 'private'."
  />
  <ApiCard
    type="property"
    sig="public bool $isGroup"
    returns="bool"
    badge="Property Hook"
    desc="Returns true if chat type is 'group'."
  />
  <ApiCard
    type="property"
    sig="public bool $isSupergroup"
    returns="bool"
    badge="Property Hook"
    desc="Returns true if chat type is 'supergroup'."
  />
  <ApiCard
    type="property"
    sig="public bool $isChannel"
    returns="bool"
    badge="Property Hook"
    desc="Returns true if chat type is 'channel'."
  />
  <ApiCard
    type="property"
    sig="public ?string $mention"
    returns="?string"
    badge="Property Hook"
    desc="Returns '@username' if present, or an HTML mention link 'tg://user?id=...' otherwise."
  />
</ApiGroup>
