# Conversational Flows (Linear Forms)

The base `Tueen\Telegram\Flow\Flow` class provides a linear state machine for multi-step textual dialogues, surveys, registration forms, and multi-prompt wizards.

```mermaid
flowchart TD
    Trigger(["User triggers /register or $bot->startFlow()"]) --> StepStart

    subgraph StepStart ["Step 1: start()"]
        PromptName["🤖 Bot: 'Welcome! What is your full name?'"]
    end

    StepStart --> UserEntersName[/"👤 User sends Name"/] --> StepEmail

    subgraph StepEmail ["Step 2: askEmail()"]
        CheckName{"Name length >= 2?"}
        CheckName -->|No| RepromptName["⚠️ $this->stay()<br/>'Name too short, please re-enter'"]
        RepromptName -.-> UserEntersName
        CheckName -->|Yes| PromptEmail["🤖 Bot: 'Great! What is your email address?'"]
    end

    StepEmail --> UserEntersEmail[/"👤 User sends Email"/] --> StepConfirm

    subgraph StepConfirm ["Step 3: confirm()"]
        CheckEmail{"Valid Email Regex?"}
        CheckEmail -->|No| RepromptEmail["⚠️ $this->stay()<br/>'Invalid email format, try again'"]
        RepromptEmail -.-> UserEntersEmail
        CheckEmail -->|Yes| PromptConfirm["🤖 Bot: 'Confirm details? (yes/no)'"]
        
        Decision{"User Response"}
        PromptConfirm --> Decision
        Decision -->|User sends /cancel| CancelFlow["🚫 $this->cancel()<br/><i>Triggers onExit('cancelled')</i>"]
        Decision -->|User sends 'yes'| FinishFlow["✅ $this->finish()<br/><i>Persists record & finishes</i>"]
    end
```

---

## 🏛️ Anatomy of a Conversational Flow

Every step in a `Flow` is a public method that receives the incoming Telegram `Update`:

```php
namespace App\Flows;

use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Types\Update;

class RegistrationFlow extends Flow
{
    public function start(Update $update): void
    {
        $this->bot->sendMessage(chatId: $this->chatId, text: 'Welcome! What is your full name?');
        $this->to('askEmail');
    }

    public function askEmail(Update $update): void
    {
        $name = trim($update->findAnyText() ?? '');

        if (strlen($name) < 2) {
            $this->stay('Name must be at least 2 characters. Please try again:');
            return;
        }

        $this->set('name', $name);
        $this->bot->sendMessage(chatId: $this->chatId, text: "Nice to meet you, {$name}! What is your email?");
        $this->to('confirm');
    }

    public function confirm(Update $update): void
    {
        $email = trim($update->findAnyText() ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->stay('Invalid email format. Please enter a valid email:');
            return;
        }

        $this->set('email', $email);
        $name = $this->get('name');

        $this->bot->sendMessage(
            chatId: $this->chatId,
            text: "✅ Registration complete for {$name} ({$email})!"
        );

        $this->finish();
    }
}
```

---

## 🧭 Step Navigation Methods

### `$this->to(string $stepMethod, array $data = []): static`
Moves the user to the specified step method on their next incoming update.
- Records the previous step in `$state->history` for back-navigation.
- Merges optional key-value `$data` into session storage.
- Automatically saves state and refreshes session TTL.

### `$this->stay(?string $replyMessage = null, mixed $keyboard = null): static`
Retains the user on the current step without appending to history.
- Sends an optional validation error prompt.
- Optionally displays a keyboard.

### `$this->back(?string $replyMessage = null): static`
Navigates to the previous step in history, or completes the flow if history is empty.

### `$this->finish(): void`
Completes the flow, removes session state from storage, and executes `onExit($update, 'finished')`.

### `$this->cancel(?string $replyMessage = 'Operation cancelled.'): void`
Cancels the flow, purges stored state, sends the optional cancellation message, and triggers `onExit($update, 'cancelled')`.

---

## 🎯 Filtering Allowed Updates (`allowedUpdates` & `#[AllowedUpdates]`)

By default, an active Flow intercepts all incoming updates for that user/chat. However, you can restrict which update types enter a Flow using either property, attribute, or global configuration:

### Using the `#[AllowedUpdates]` Attribute
```php
use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Flow\Attributes\AllowedUpdates;
use Tueen\Telegram\Enums\UpdateType;

#[AllowedUpdates('message', 'callback_query')]
// Or with enums: #[AllowedUpdates(UpdateType::MESSAGE, UpdateType::CALLBACK_QUERY)]
class SurveyFlow extends Flow
{
    public function start(Update $update): void
    {
        // ...
    }
}
```

### Using the `$allowedUpdates` Property or Method
```php
class CustomFlow extends Flow
{
    protected array $allowedUpdates = ['message'];

    // Or dynamic check:
    public function allowsUpdate(Update $update): bool
    {
        return $update->isMessage();
    }
}
```

### Global Default via Configuration
```php
$config = Telegram::create('TOKEN')
    ->withFlowAllowedUpdates(['message', 'callback_query'])
    ->build();
```

> [!TIP]
> When an update type is not allowed for an active flow (for example, a `chat_join_request` or `inline_query`), the flow **leaves the user's active session intact** and allows the update to pass through to your regular bot routes and attribute controllers!

---

## 🕹️ External Flow Inspection & Control (`$bot->flow()`)

You can inspect and control active conversation flows from anywhere outside the flow — such as inside command handlers, route controllers, update middlewares, or background jobs.

### The Fluent `FlowSession` Interface
Call `$bot->flow()` (which automatically resolves `chatId` and `userId` from the current update), or pass explicit IDs:

```php
$bot->onCommand('status', function (Update $update, Telegram $bot) {
    $session = $bot->flow();

    if ($session->isActive) {
        $flowClass = $session->class; // e.g. App\Flows\OrderFlow
        $step = $session->step;       // e.g. 'askQuantity'
        $data = $session->data;       // ['item_id' => 42]

        $bot->sendMessage(
            chatId: $update->chat->id,
            text: "You are currently in {$flowClass} on step: {$step}"
        );
    }
});
```

### Controlling Flows from Outside
You can programmatically navigate, modify, or terminate flows:

```php
$session = $bot->flow();

// Navigate back to previous step:
$session->back('Returning to previous question...');

// Jump directly to another step:
$session->to('confirm', ['reviewed' => true]);

// Transition seamlessly to a different flow:
$session->jumpTo(HelpFlow::class);

// Reset flow back to start step and clear data:
$session->reset();

// Cancel or finish the active flow:
$session->cancel('Flow was cancelled.');
$session->finish();

// Directly mutate state data:
$session->set('discount_code', 'SUMMER2026');
$code = $session->get('discount_code');
```

### Direct Facade Shortcuts on `$bot` & `$app`
For quick checks, convenient one-line helpers are available directly on `$bot` and `$app`:

```php
if ($bot->hasActiveFlow()) {
    $activeClass = $bot->getActiveFlowClass();
    $bot->flowBack();   // Navigate back
    $bot->cancelFlow(); // Cancel active flow
    $bot->finishFlow(); // Finish active flow
}
```
