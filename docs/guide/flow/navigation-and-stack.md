# Navigation Stack & Root Flow

`InteractiveFlow` features a hierarchical navigation stack that models menus and multi-layered screens just like modern UI frameworks.

```mermaid
graph TD
    MainMenu["MainMenuFlow (root)"]
    Settings["SettingsFlow"]
    Notifications["NotificationsFlow"]

    MainMenu -->|"$this->push(SettingsFlow::class)"| Settings
    Settings -->|"$this->push(NotificationsFlow::class)"| Notifications
    Notifications -->|"$this->pop()"| Settings
    Settings -->|"$this->pop()"| MainMenu
    Notifications -->|"Click 🏠 Home"| MainMenu
```

---

## 🥞 Stack Navigation: `push()` and `pop()`

### `$this->push(string $childFlowClass, array $data = []): void`
Suspends the active flow, pushes its state onto `$state->flowStack`, and opens the child flow on the **exact same message ID** for zero-flicker transitions.

```php
#[Action('open_profile')]
public function openProfile(): void
{
    $this->push(UserProfileFlow::class, ['user_id' => 123]);
}
```

### `$this->pop(mixed $result = null): void`
Pops the active flow off the stack and restores the parent flow on the same message ID, executing the parent's `onResume($result)` hook with any returned data.

```php
#[Action('save_selection')]
public function saveSelection(): void
{
    $this->pop('English (US)');
}
```

---

## 🧭 Navigation Buttons: `🔙` Back and `🏠` Home

`Screen` includes a navigation bar with language-neutral emoji defaults:
- **Back Button:** `🔙` (triggers `$this->pop()`)
- **Home Button:** `🏠` (resets flow stack and jumps to `rootFlow`)

```php
// Enable both Back and Home:
Screen::make('Select an option:')
    ->withNavigation(back: true, home: true);

// Enable only Back:
Screen::make('Select an option:')
    ->withNavigation(back: true, home: false);

// Customizing labels:
Screen::make('Select an option:')
    ->withNavigation(
        back: true,
        home: true,
        backLabel: '🔙 Return',
        homeLabel: '🏠 Main Menu'
    );
```

> [!TIP]
> Navigation buttons work seamlessly on both **Inline Keyboards** (via callback query) and **Reply Keyboards** (via emoji text message `🔙` or `🏠`).

---

## 🏠 Configuring `rootFlow` & Handling `/start`

You can configure a global root flow on the client or FlowManager:

```php
$bot->setRootFlow(MainMenuFlow::class);

// Or through Config builder:
$config = Config::builder()
    ->token('...')
    ->rootFlow(MainMenuFlow::class)
    ->build();
```

### Global `/start` Behavior
When a user issues `/start` while in an active flow:
1. `FlowManager` checks `$activeFlow->canInterrupt($update)`.
2. If `true` (default), the entire flow stack is cleared, and the user is redirected to `rootFlow`.
3. If `false` (e.g. during checkout or an active form), the `/start` command is blocked, and the user remains safely in the active step.

---

## 🍞 Breadcrumbs Trail (`withBreadcrumbs`)

When nesting multi-level menus (e.g. `Catalog` » `Electronics` » `Headphones`), orient the user with an automatic breadcrumbs trail:

```php
use Tueen\Telegram\Flow\Attributes\Breadcrumb;

#[Breadcrumb('Headphones')]
class HeadphoneDetailsFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make("Sony WH-1000XM5 Specs...")
            ->withBreadcrumbs(separator: ' » ')
            ->inline(...)
            ->withNavigation(back: true, home: true);
    }
}
```

This prefixes your screen text with:
```text
Catalog » Electronics » Headphones

Sony WH-1000XM5 Specs...
```

### Dynamic Breadcrumb Titles
If your breadcrumb title depends on dynamic state data (such as product name loaded from database), override `getBreadcrumbTitle()`:

```php
public function getBreadcrumbTitle(): string
{
    return $this->get('product_name', 'Item');
}
```

