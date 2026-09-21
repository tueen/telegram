# Methods & Types

`tueen/telegram` provides full, native support for all 185 Telegram Bot API methods and 400+ Types with 100% strict typing, PHP 8.4 asymmetric visibility, and native Enums.

---

## Method Invocations

You can invoke API methods either dynamically or via dedicated Method classes:

### 1. Dynamic Method Calls (Recommended)

Call any Telegram method directly using camelCase and named arguments:

```php
use Tueen\Telegram\Enums\ParseMode;

$message = $telegram->sendMessage(
    chatId: 12345678,
    text: "Hello from <b>Tueen</b>!",
    parseMode: ParseMode::HTML // Direct Enum instance
);
```

Full IDE autocompletion and parameter docblocks are provided via the `@mixin TelegramMethods` contract.

### 2. Method Objects (For Custom Pipelines & Architecture)

Every Telegram Bot API method has a dedicated class in `Tueen\Telegram\Methods`:

```php
use Tueen\Telegram\Methods\SendMessage;

$method = new SendMessage(
    chatId: 12345678,
    text: "Hello from Tueen!"
);

$message = $telegram->send($method);
```

### 3. Forward-Compatible Unknown / New Parameters

If Telegram introduces a new parameter before the library updates, you can pass it immediately as a named parameter or via variadic `$extra`:

```php
// If Telegram adds 'new_feature_flag' tomorrow:
$message = $telegram->sendMessage(
    chatId: 12345678,
    text: "Testing new feature",
    newFeatureFlag: true // Seamlessly passed to the API
);
```

---

## Types & Property Access

All API responses are automatically deserialized into strongly-typed objects in `Tueen\Telegram\Types`.

### Dual Access: camelCase and snake_case

```php
// CamelCase property access (PHP 8.4 asymmetric visibility)
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
  $result = $telegram->deleteMessage(chatId: 123, messageId: 456);
  if ($result->ok() && $result->value) {
      echo "Deleted successfully";
  }
  ```
- **`IntegerResult`** (e.g. from `getChatMemberCount`)
  ```php
  $count = $telegram->getChatMemberCount(chatId: -100123);
  echo "Members: " . $count->value;
  ```
- **`StringResult`** (e.g. from `exportChatInviteLink`, `createInvoiceLink`)
  ```php
  $link = $telegram->exportChatInviteLink(chatId: -100123);
  echo "Invite link: " . $link->value;
  ```
- **`ArrayResult<T>`** (e.g. from `getUpdates`, `forwardMessages`)
  ```php
  $updates = $telegram->getUpdates();
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
