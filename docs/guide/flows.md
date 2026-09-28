# Multi-Step Conversation Flows (`Flow`)

When building Telegram bots, multi-step user interactions (such as registration wizards, checkout processes, surveys, or feedback forms) often become messy when managed with manual `if/else` checks or database state flags.

`tueen/telegram` introduces **`Flow`** — a modern, lightweight, class-based state machine designed specifically for Telegram bots.

---

## 1. Core Principles of `Flow`

- **Class-Based Structure:** Each conversational flow is an isolated class extending `Flow`.
- **Steps are Methods:** Every step in the flow is simply a public method on the class.
- **Intuitive Step Controls:** Navigate seamlessly with `$this->to()`, `$this->stay()`, `$this->back()`, `$this->jumpTo()`, and `$this->finish()`.
- **State Storage Drivers:** Built-in lightweight drivers: `MemoryStateStore` (in-memory) and `FileStateStore` (JSON files on disk).
- **Auto-Prioritization:** When a user is in an active Flow, their incoming updates are automatically routed to that Flow before reaching general bot commands or fallback routes.

---

## 2. Quick Example: A Registration Flow

```php
namespace App\Flows;

use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Types\Update;

class RegistrationFlow extends Flow
{
    // Step 1: Initial Entry Step
    public function start(Update $update): void
    {
        $this->bot->sendMessage(
            chatId: $this->chatId,
            text: 'Welcome! What is your full name?'
        );

        // Advance to step 2:
        $this->to('askEmail');
    }

    // Step 2: Receive name and ask for email
    public function askEmail(Update $update): void
    {
        $name = trim($update->findAnyText() ?? '');

        // Validation: Stay on current step with error message
        if (mb_strlen($name) < 3) {
            $this->stay('Name is too short. Please enter at least 3 characters:');
            return;
        }

        // Store data in Flow session:
        $this->set('name', $name);

        $this->bot->sendMessage(
            chatId: $this->chatId,
            text: "Nice to meet you, {$name}! What is your email address?"
        );

        // Advance to step 3:
        $this->to('confirm');
    }

    // Step 3: Receive email and finalize
    public function confirm(Update $update): void
    {
        $email = trim($update->findAnyText() ?? '');

        // Allow user to go back to the previous step:
        if ($email === 'back' || $email === '/back') {
            $this->back('Going back! Please enter your name again:');
            return;
        }

        $name = $this->get('name');

        $this->bot->sendMessage(
            chatId: $this->chatId,
            text: "Registration complete for {$name} ({$email})!"
        );

        // Clean up flow session state:
        $this->finish();
    }
}
```

---

## 3. Starting a Flow

Trigger a Flow from any command handler, callback query, or controller:

```php
use App\Flows\RegistrationFlow;

// In a command handler:
$bot->onCommand('register', function (Update $update, Telegram $bot) {
    $bot->startFlow(RegistrationFlow::class);
});

// Or pass custom initial data:
$bot->onCallbackQuery('onboard', function (Update $update, Telegram $bot) {
    $bot->startFlow(RegistrationFlow::class, initialData: ['source' => 'inline_button']);
});
```

---

## 4. Flow Navigation Methods

Inside any step method, you have access to expressive flow control:

| Method | Description |
| :--- | :--- |
| **`$this->to('stepName', $data)`** | Advances the user to the specified method on the next incoming update. |
| **`$this->stay(?string $replyMsg)`** | Keeps the user on the current step, optionally sending a reply message. |
| **`$this->back(?string $replyMsg)`** | Pops the last step from history and navigates backward. |
| **`$this->finish()`** | Marks the flow as successfully finished and purges its stored session state. |
| **`$this->cancel(?string $replyMsg)`** | Aborts the flow, clears session data, and optionally informs the user. |
| **`$this->jumpTo(OtherFlow::class)`** | Seamlessly hands off the user to another Flow class, carrying over stored data. |

---

## 5. Flow Data Management

Data collected across steps can be stored directly within the Flow session:

```php
// Fluent setter & getter:
$this->set('product_id', 42);
$productId = $this->get('product_id', default: null);

// Magic property access:
$this->userAge = 25;
echo $this->userAge; // 25

// Check or remove:
$this->has('product_id'); // true
$this->remove('product_id');
$allData = $this->all();
```

---

## 6. Exit Commands & Cancellation

Users should never get trapped in a flow. By default, incoming messages matching `/cancel`, `/exit`, or `/stop` automatically cancel the active flow:

```php
class OrderFlow extends Flow
{
    // Customize exit commands:
    protected array $exitCommands = ['/cancel', '/quit', 'cancel'];

    // Optional lifecycle hook when exiting:
    public function onExit(Update $update, string $reason): void
    {
        // $reason is 'finished', 'cancelled', or 'interrupted'
        if ($reason === 'cancelled') {
            // Log cancellation or release reserved cart items
        }
    }
}
```

---

## 7. Storage Drivers (`StateStoreInterface`)

Tueen provides four storage drivers out of the box:

### 1. `MemoryStateStore` (Default)
Stores active flows in PHP memory. Ideal for testing, CLI long-polling workers, or local development:

```php
use Tueen\Telegram\Flow\Storage\MemoryStateStore;

$bot->setFlowStore(new MemoryStateStore());
```

### 2. `RedisStateStore` (Distributed)
High-performance distributed state store for multi-server webhook environments. Supports native `\Redis` and `Predis`:

```php
use Tueen\Telegram\Flow\Storage\RedisStateStore;

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$bot->setFlowStore(new RedisStateStore($redis, prefix: 'my_bot_flow:'));
```

### 3. `Psr16StateStore` (PSR-16 Cache)
Integrates seamlessly with any PSR-16 compliant cache library (Laravel, Symfony Cache, etc.):

```php
use Tueen\Telegram\Flow\Storage\Psr16StateStore;

// In Laravel: Cache::store('redis') or standard PSR-16 cache
$bot->setFlowStore(new Psr16StateStore($psr16CacheInstance));
```

### 4. `FileStateStore`
Stores serialized states as JSON files in a local directory (defaults to `sys_get_temp_dir() . '/tueen_flows'`). Zero external dependencies:

```php
use Tueen\Telegram\Flow\Storage\FileStateStore;

$bot->setFlowStore(new FileStateStore('/var/run/telegram_flows'));
```

### Custom Cache Drivers
You can implement `StateStoreInterface` to store sessions in any database or custom driver:

```php
use Tueen\Telegram\Flow\Storage\StateStoreInterface;
use Tueen\Telegram\Flow\FlowState;

class CustomDatabaseStateStore implements StateStoreInterface
{
    public function get(string $key): ?FlowState { /* ... */ }
    public function set(string $key, FlowState $state, ?int $ttl = null): void { /* ... */ }
    public function delete(string $key): void { /* ... */ }
    public function clear(): void { /* ... */ }
}
```
