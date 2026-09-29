# Keyboards & Action Binding

`InteractiveFlow` decouples callback routing from tedious manual string checking using modern PHP 8 attributes: `#[Action]` and `#[Input]`.

---

## 🎯 The `#[Action]` Attribute

Methods annotated with `#[Action('pattern')]` are automatically executed when the user clicks an inline keyboard button matching the specified pattern.

### 1. Literal Action Patterns
```php
use Tueen\Telegram\Flow\InteractiveFlow;
use Tueen\Telegram\Flow\Screen;
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Keyboards\InlineKeyboard;

class SettingsFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Settings Menu')
            ->inline(fn (InlineKeyboard $k) => $k
                ->action('Toggle Dark Mode', 'toggle_dark')
                ->action('Language', 'language')
            );
    }

    #[Action('toggle_dark')]
    public function toggleDarkMode(): void
    {
        $current = $this->get('dark', false);
        $this->set('dark', !$current);
        $this->refresh();
    }
}
```

### 2. Parameterized Action Patterns
You can capture dynamic URL-style parameters in callback data using `{param}` placeholders:

```php
public function render(): Screen
{
    return Screen::make('Select an Item:')
        ->inline(fn (InlineKeyboard $k) => $k
            ->action('Item 10', 'item_10')
            ->action('Item 25', 'item_25')
        );
}

#[Action('item_{id}')]
public function selectItem(int $id): void
{
    $this->set('selected_id', $id);
    $this->push(ItemDetailsFlow::class, ['item_id' => $id]);
}
```
The parameter is automatically converted to the type hinted in the method signature (`int`, `string`, etc.).

---

## ✍️ Free-Text Inputs with `#[Input]`

If a screen needs to accept free-form text from the user while keeping the interactive screen active:

```php
use Tueen\Telegram\Flow\Attributes\Input;
use Tueen\Telegram\Types\Update;

#[Input]
public function handleTextInput(Update $update): void
{
    $query = trim($update->findAnyText() ?? '');
    $this->set('search_query', $query);
    $this->refresh();
}
```

---

## 🎛️ Switching Keyboard Types Seamlessly

`Screen` lets you switch seamlessly between inline keyboards, native reply keyboards, and removing keyboards:

```php
// Switch to a native contact sharing keyboard:
Screen::make('Please share your phone number:')
    ->reply(fn (ReplyKeyboard $k) => $k
        ->requestContact('📱 Share Contact')
        ->resize()
        ->oneTime()
    );

// Remove any existing reply keyboards:
Screen::make('Processing your request...')
    ->removeKeyboard();
```

> [!NOTE]
> Telegram's Bot API does not allow converting an inline message into a native reply keyboard via `editMessage`. When transitioning from an inline keyboard to a `ReplyKeyboard`, `InteractiveFlow` automatically sends a new message with the reply keyboard and tracks the new `$this->messageId`.

---

## 📄 Automatic Pagination Bar

When rendering paginated items or catalogues, add an automatic pagination control row directly using `withPagination()`:

```php
Screen::make("Products (Page {$page} of {$totalPages}):")
    ->inline(...)
    ->withPagination(
        currentPage: $page,
        totalPages: $totalPages,
        actionPattern: 'page_{page}', // Generates [◀️] [2/5] [▶️]
        prevLabel: '◀️',
        nextLabel: '▶️'
    )
    ->withNavigation(back: true, home: true);
```

Then bind the page action with a parameterized `#[Action]`:

```php
#[Action('page_{page}')]
public function changePage(int $page): void
{
    $this->set('currentPage', $page);
    $this->refresh();
}
```

---

## ⏱️ Action Rate-Limiting & Debouncing (`#[Debounce]`)

Users in Telegram often double-tap buttons impulsively, which can cause duplicate orders, race conditions, or duplicate payment requests.

Annotate any `#[Action]` method with `#[Debounce]` to lock rapid button clicks:

```php
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Flow\Attributes\Debounce;

#[Action('checkout')]
#[Debounce(seconds: 3.0, notice: '⏳ Please wait, processing your payment...', showAlert: false)]
public function handleCheckout(): void
{
    // Executes at most once every 3 seconds per user
    $this->processPayment();
}
```

- **`seconds`**: Debounce window in seconds (supports fractional seconds like `1.5`).
- **`notice`**: The toast notification or alert message shown to the user when throttled.
- **`showAlert`**: If `true`, pops up a modal alert dialog; if `false` (default), shows a non-intrusive toast popup.

---

## 🛡️ Channel Membership Guard (`#[RequireMember]`)

Gate actions or screens behind mandatory Telegram channel or supergroup subscriptions. The `#[RequireMember]` attribute verifies that the user is currently a member, administrator, or creator:

```php
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Flow\Attributes\RequireMember;

#[Action('claim_bonus')]
#[RequireMember(
    channel: '@tueen_channel',
    fallbackMessage: '🔒 You must join our official channel to claim this daily reward!',
    joinUrl: 'https://t.me/tueen_channel'
)]
public function claimDailyBonus(): void
{
    // Executed ONLY if the user is a member/admin/creator of @tueen_channel
    $this->grantBonus(100);
    $this->toast('🎁 Bonus claimed!');
    $this->refresh();
}
```

### Guard Workflow:
1. `InteractiveFlow` queries Telegram's `getChatMember(chat_id, user_id)`.
2. If member status is `creator`, `administrator`, or `member`, execution proceeds to the action method.
3. If not joined (or `left`, `kicked`, `restricted`), execution is halted. If `joinUrl` is specified, an inline screen with a direct "📢 Join Channel" button and a "🔄 Check Again" refresh button is automatically rendered for the user.

