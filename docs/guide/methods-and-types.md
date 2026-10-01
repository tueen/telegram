# Calling Methods & Types

`tueen/telegram` provides native, strictly typed support for Telegram Bot API methods and types with full IDE autocompletion, enums, computed property hooks, and forward-compatible fallbacks.

---

## 🚀 1. Method Invocations

You can invoke API methods either dynamically or via dedicated Method classes:

### Dynamic Method Calls (Recommended)

Call any Telegram method directly using camelCase and named arguments:

```php
use Tueen\Telegram\Enums\ParseMode;

$message = $bot->sendMessage(
    chatId: 12345678,
    text: "Hello from <b>Tueen</b>!",
    parseMode: ParseMode::HTML // Direct Enum instance
);
```

::: tip Recommended: Use PHP Named Arguments
In `tueen/telegram`, **always invoke API methods using PHP named arguments** (`paramName: $value`).

```php
// ✅ RECOMMENDED: Explicit, readable, and order-independent
$bot->sendMessage(
    text: "Welcome to our bot!",
    parseMode: ParseMode::HTML
);

// ❌ AVOID: Fragile positional arguments
$bot->sendMessage(123456, "Hello", null, null, null, null, 'HTML');
```

#### Why Named Arguments Are Recommended:
1. **Contextual Auto-Injection:** Because parameters like `chatId`, `businessConnectionId`, and query IDs are automatically inferred from the active update, named arguments allow you to supply only the parameters you need (e.g. `text: "..."`) without worrying about parameter position.
2. **No Trailing Nulls:** Telegram Bot API methods accept dozens of optional parameters. Named arguments eliminate the need for long lists of `null` placeholders.
3. **Forward-Compatibility:** When Telegram adds new parameters to Bot API methods, named arguments prevent positional shift bugs.
4. **Self-Documenting:** Code is clear and easy to read during code review.
:::

Full IDE autocompletion and parameter docblocks are provided via the `@mixin \Tueen\Telegram\Contracts\TelegramMethods` contract.

### Method Objects (For Custom Pipelines & Architecture)

Every Telegram Bot API method has a dedicated class in `Tueen\Telegram\Methods`:

```php
use Tueen\Telegram\Methods\SendMessage;

$method = new SendMessage(
    chatId: 12345678,
    text: "Hello from Tueen!"
);

$message = $bot->send($method);
```

### Forward-Compatible Unknown / New Parameters

If Telegram introduces a new parameter before the library updates, you can pass it immediately as a named parameter or via variadic `$extra`:

```php
// If Telegram adds 'new_feature_flag' tomorrow:
$message = $bot->sendMessage(
    chatId: 12345678,
    text: "Testing new feature",
    newFeatureFlag: true // Seamlessly passed to the API
);
```

### Passing Complex & Nested Types (Objects or Arrays)

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

## 🏛️ 2. The Universal Base `Type` Class

All Telegram Bot API response models (over 400 types such as `Message`, `User`, `Chat`, `Update`) extend the base class `Tueen\Telegram\Types\Type`.

### Core Features of `Type`:
1. **Universal `ok(): bool` and `isOk(): bool`:** Every response object guarantees a boolean success check. Real response types always return `true`, while `Tueen\Telegram\Types\Error` objects return `false`.
2. **Dual Property Access:** Access properties using camelCase (`$message->messageId`) or snake_case (`$message->message_id`).
3. **`ArrayAccess` Support:** Access properties using bracket syntax (`$message['chat']['id']`).
4. **`JsonSerializable` & Stringable:** Passing any type to `json_encode($type)` or `(string)$type` produces clean, unescaped JSON.
5. **Array Transformation:** Call `toArray()` or `toJson()` to recursively convert the type and all nested objects into primitive arrays or JSON strings.
6. **Bulletproof Forward-Compatibility:** If Telegram introduces new fields in the future, they are preserved inside `$extra` and accessible dynamically. If Telegram returns an entirely new, unmapped object type, Tueen falls back gracefully to the base `Type` class without crashing.

---

## ⚙️ 3. The Base `Method` Class

Every Telegram Bot API method is represented by a class extending `Tueen\Telegram\Methods\Method`.

### Core Responsibilities of `Method`:
1. **Endpoint Resolution:** Defines the API method name (e.g. `'sendMessage'`).
2. **Payload Serialization:** Prepares and filters parameters, converting nested `Type` objects, Enums, and JSON-encoded fields.
3. **Multipart & File Upload Detection:** Inspects parameters for `InputFile` instances. If any file is present, `isMultipart()` returns `true` and the HTTP client uploads via `multipart/form-data`.
4. **Typed Return Resolution:** Defines `returnType()` so the HTTP client automatically instantiates and deserializes the response into the appropriate concrete `Type` (e.g. `Message::class`, `BooleanResult::class`).

---

## 🧩 4. Concerns & Computed Helpers

Rather than bloating generated classes, helper methods and computed properties are organized into reusable traits inside `Tueen\Telegram\Types\Concerns\`:

<ApiGroup description="Reusable traits enhancing generated Telegram Bot API types with computed property hooks and helpers.">
  <ApiCard
    sig="HasUpdateHelpers"
    returns="Trait on Update"
    badge="Concern Trait"
    desc="Equips Update with $type, $chatId, $userId, $messageId, $fileId, findMessage(), findUser(), findChat(), and isType()."
  />
  <ApiCard
    sig="HasMessageHelpers"
    returns="Trait on Message"
    badge="Concern Trait"
    desc="Equips Message with $isCommand, $command, $args, $textOrCaption, $fileId, $largestPhoto, isRepliedToMessage(), and findAnyText()."
  />
  <ApiCard
    sig="HasChatHelpers"
    returns="Trait on Chat"
    badge="Concern Trait"
    desc="Equips Chat with computed $fullName, $isPrivate, $isGroup, $isSupergroup, and $isChannel."
  />
  <ApiCard
    sig="HasUserHelpers"
    returns="Trait on User"
    badge="Concern Trait"
    desc="Equips User with computed $fullName and HTML $mention."
  />
  <ApiCard
    sig="HasInlineKeyboardHelpers"
    returns="Trait on InlineKeyboardMarkup"
    badge="Concern Trait"
    desc="Equips InlineKeyboardMarkup with button query and row inspection helpers."
  />
  <ApiCard
    sig="HasReplyKeyboardHelpers"
    returns="Trait on ReplyKeyboardMarkup"
    badge="Concern Trait"
    desc="Equips ReplyKeyboardMarkup with button grid inspection helpers."
  />
</ApiGroup>

> For a complete guide and code examples of these helpers, see [Update & Message Helpers](./update-and-message-helpers).

---

## 📦 5. Custom Result Wrapper Types

When Telegram Bot API methods return primitives (such as boolean values, integer counts, or plain strings), Tueen wraps them in dedicated classes inside `Tueen\Telegram\Types\Custom\` so you can always check `$result->ok()`:

- **`BooleanResult`** (e.g. from `setWebhook`, `deleteMessage`, `pinChatMessage`): Wraps a `bool $value`.
- **`IntegerResult`** (e.g. from `getChatMemberCount`): Wraps an `int $value`.
- **`StringResult`** (e.g. from `exportChatInviteLink`, `createInvoiceLink`): Wraps a `string $value`.
- **`ArrayResult<T>`** (e.g. from `getUpdates`, `forwardMessages`): An iterable collection wrapping arrays of typed objects.
- **`InputFile`**: Universal multipart file wrapper for uploads (`fromPath`, `fromResource`, `fromString`, `fromStream`).

---

## 🧭 6. Methods & Types API Reference

Below is the comprehensive catalog of core methods and properties across the Base `Type`, Base `Method`, Contextual Auto-Injection, and Custom Wrapper types.

### 🏛️ Base `Type` Class (`Tueen\Telegram\Types\Type`)

<ApiGroup description="Universal base class for all 400 Telegram Bot API response types with dual error handling, serialization, and ArrayAccess.">
  <ApiCard
    sig="ok(): bool"
    returns="bool"
    badge="Universal Check"
    desc="Determines whether the API response succeeded. Always returns true for valid Type instances and false for Error objects."
  />
  <ApiCard
    sig="isOk(): bool"
    returns="bool"
    badge="Alias"
    aliasFor="ok()"
    desc="Shorthand alias for ok() adhering to standard boolean accessor naming."
  />
  <ApiCard
    type="property"
    sig="public array $rawData"
    returns="array"
    badge="Property Hook"
    desc="Virtual property returning the original raw payload array received from Telegram API."
  />
  <ApiCard
    type="property"
    sig="protected(set) array $raw"
    returns="array"
    badge="Asymmetric Visibility"
    desc="Internal raw array populated during type construction."
  />
  <ApiCard
    sig="toArray(): array"
    returns="array"
    badge="Serialization"
    desc="Recursively converts the type instance and all nested Type objects and Enums into a clean associative array."
  />
  <ApiCard
    sig="toJson(int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT): string"
    returns="string"
    badge="Serialization"
    desc="Serializes the type instance into formatted JSON."
  />
  <ApiCard
    sig="offsetGet(mixed $offset): mixed"
    returns="mixed"
    badge="ArrayAccess"
    desc="ArrayAccess getter supporting snake_case field access (e.g. $message['chat']['id'])."
  />
  <ApiCard
    sig="offsetExists(mixed $offset): bool"
    returns="bool"
    badge="ArrayAccess"
    desc="ArrayAccess isset check for verifying whether a property exists."
  />
  <ApiCard
    sig="offsetSet(mixed $offset, mixed $value): void"
    returns="void"
    badge="ArrayAccess"
    desc="ArrayAccess setter for modifying or injecting dynamic fields."
  />
  <ApiCard
    sig="offsetUnset(mixed $offset): void"
    returns="void"
    badge="ArrayAccess"
    desc="ArrayAccess unsetter for clearing fields."
  />
</ApiGroup>

---

### ⚙️ Base `Method` Class (`Tueen\Telegram\Methods\Method`)

<ApiGroup description="Base request class for all 150 Telegram Bot API methods handling multipart encoding and serialization.">
  <ApiCard
    sig="endpoint(): string"
    returns="string"
    badge="Contract"
    desc="Returns the Telegram Bot API endpoint method name (e.g. 'sendMessage', 'sendPhoto')."
  />
  <ApiCard
    sig="getPayload(): array"
    returns="array"
    badge="Contract"
    desc="Prepares and serializes method parameters into a normalized array ready for JSON or multipart transmission."
  />
  <ApiCard
    sig="hasUploads(): bool"
    returns="bool"
    badge="Multipart"
    desc="Detects whether any parameter contains an InputFile or file stream requiring multipart/form-data encoding."
  />
  <ApiCard
    sig="isMultipart(): bool"
    returns="bool"
    badge="Alias"
    aliasFor="hasUploads()"
    desc="Convenience alias to check if request must be dispatched using multipart encoding."
  />
  <ApiCard
    sig="returnType(): string"
    returns="class-string"
    badge="Contract"
    desc="Returns the concrete Type class name to deserialize the response into (e.g. Message::class, BooleanResult::class)."
  />
</ApiGroup>

---

### 🎯 Contextual Parameter Auto-Injection

<ApiGroup description="Repetitive parameters automatically resolved and injected from the active Update when omitted from method calls.">
  <ApiCard
    sig="chat_id"
    returns="int|string"
    badge="Contextual"
    desc="Inferred from active message, callback query, business message, channel post, or reaction."
  />
  <ApiCard
    sig="business_connection_id"
    returns="string"
    badge="Contextual"
    desc="Inferred from incoming business connection or business message update."
  />
  <ApiCard
    sig="message_thread_id"
    returns="int"
    badge="Contextual"
    desc="Inferred from forum topic thread ID on incoming message."
  />
  <ApiCard
    sig="user_id"
    returns="int"
    badge="Contextual"
    desc="Inferred from acting user (for getUserProfilePhotos, getUserGifts, etc.)."
  />
  <ApiCard
    sig="message_id"
    returns="int"
    badge="Contextual"
    desc="Inferred from primary message (for deleteMessage, pinChatMessage, etc.)."
  />
  <ApiCard
    sig="inline_message_id"
    returns="string"
    badge="Contextual"
    desc="Inferred from inline callback queries for editMessageText, editMessageReplyMarkup, etc."
  />
  <ApiCard
    sig="callback_query_id"
    returns="string"
    badge="Contextual"
    desc="Inferred query ID for answerCallbackQuery(text: 'Done!')."
  />
  <ApiCard
    sig="inline_query_id"
    returns="string"
    badge="Contextual"
    desc="Inferred query ID for answerInlineQuery(results: [...])."
  />
  <ApiCard
    sig="shipping_query_id"
    returns="string"
    badge="Contextual"
    desc="Inferred query ID for answerShippingQuery(ok: true)."
  />
  <ApiCard
    sig="pre_checkout_query_id"
    returns="string"
    badge="Contextual"
    desc="Inferred query ID for answerPreCheckoutQuery(ok: true)."
  />
</ApiGroup>

---

### 📦 Custom Wrapper Types (`Tueen\Telegram\Types\Custom\`)

<ApiGroup description="Typed wrappers for primitive results and multipart file transfers.">
  <ApiCard
    type="property"
    sig="public readonly bool $value"
    returns="bool"
    badge="BooleanResult"
    desc="Underlying boolean outcome for methods like deleteMessage, setWebhook, or pinChatMessage."
  />
  <ApiCard
    type="property"
    sig="public readonly int $value"
    returns="int"
    badge="IntegerResult"
    desc="Underlying integer counter for methods like getChatMemberCount."
  />
  <ApiCard
    type="property"
    sig="public readonly string $value"
    returns="string"
    badge="StringResult"
    desc="Underlying string for methods like exportChatInviteLink or createInvoiceLink."
  />
  <ApiCard
    sig="InputFile::fromPath(string $path, ?string $filename = null, ?string $contentType = null): self"
    returns="InputFile"
    badge="InputFile Factory"
    desc="Creates a multipart file upload wrapper from a local filesystem path."
  />
  <ApiCard
    sig="InputFile::fromResource(resource $resource, string $filename, ?string $contentType = null): self"
    returns="InputFile"
    badge="InputFile Factory"
    desc="Creates a multipart file upload wrapper from an open PHP stream resource (e.g. fopen)."
  />
  <ApiCard
    sig="InputFile::fromString(string $contents, string $filename, ?string $contentType = null): self"
    returns="InputFile"
    badge="InputFile Factory"
    desc="Creates an in-memory multipart file upload wrapper from raw string contents."
  />
  <ApiCard
    sig="InputFile::fromStream(StreamInterface $stream, string $filename, ?string $contentType = null): self"
    returns="InputFile"
    badge="InputFile Factory"
    desc="Creates a multipart file upload wrapper from any PSR-7 StreamInterface instance."
  />
</ApiGroup>
