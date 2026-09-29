# Flow & Interactive Screen Ecosystem

Multi-step dialogues and interactive bot screens (such as user onboarding wizards, settings panels, catalogs, e-commerce checkouts, surveys, and support ticket flows) are notoriously difficult to maintain when managed through scattered database flags, nested `if/else` statements, or naive polling handlers.

`tueen/telegram` provides a unified, two-tier architecture for conversational and interactive bot applications:
1. **[`Flow`](./conversational-flows.md):** A linear, step-by-step state machine designed for text questionnaires, registration wizards, and multi-prompt dialogues.
2. **[`InteractiveFlow`](./interactive-screens.md):** A screen-oriented, component-based engine featuring in-place message editing (zero-flicker UI updates), comprehensive lifecycle hooks, hierarchical navigation stacks (`push`/`pop`), and declarative action routing (`#[Action]`).

---

## 🏛️ Two-Tier Architecture Comparison

| Feature | Standard `Flow` | `InteractiveFlow` |
| :--- | :--- | :--- |
| **Primary Paradigm** | Linear Step-by-Step | Screen & Menu Component |
| **Message Updates** | Sends new messages on each step | Edits existing message in-place (`editMessageText`) |
| **Flicker-Free UI** | No (new messages each turn) | Yes (zero-flicker in-place transitions) |
| **Button Handling** | Manual step matching | Declarative `#[Action('pattern')]` attributes |
| **Navigation Bar** | Custom reply buttons | Built-in language-neutral buttons (`🔙` Back, `🏠` Home) |
| **Navigation Model** | Linear History Stack | Hierarchical Stack (`push` child flow / `pop` with results) |
| **Lifecycle Hooks** | `onExit` | `onCreate`, `onStart`, `onResume`, `onPause`, `beforeRender`, `afterRender`, `onUnhandled` |
| **Global Interruption** | Checked via `exitCommands` | Intercepted via `canInterrupt($update)` |

---

## 🧭 Flow Documentation Sitemap

Explore each section of the Flow ecosystem in detail:

- **[Conversational Flows (Linear Forms)](./conversational-flows.md):** Multi-step questionnaires, step navigation (`to`, `stay`, `back`), validation loops, and session data.
- **[Interactive Screens & Zero-Flicker](./interactive-screens.md):** In-place message rendering, `Screen` builder, zero-flicker transitions across flows, and error recovery.
- **[Lifecycle Hooks Reference](./lifecycle-hooks.md):** Deep-dive into `onCreate`, `onStart`, `onResume`, `onPause`, `beforeRender`, `afterRender`, and `canInterrupt`.
- **[Keyboards & Action Binding](./keyboards-and-actions.md):** Declarative `#[Action]` attributes, parameterized patterns (`item_{id}`), `#[Input]`, and keyboard switching.
- **[Navigation Stack & Root Flow](./navigation-and-stack.md):** Hierarchical flow stack (`push`/`pop`), `🔙` Back and `🏠` Home buttons, and configuring `rootFlow` with `/start`.
- **[State Storage & Drivers](./state-storage.md):** Zero-config session persistence using Memory, File, Redis, and PSR-16 cache drivers.

---

## ⚡ Quickstart: Interactive Menu

```php
use Tueen\Telegram\Flow\InteractiveFlow;
use Tueen\Telegram\Flow\Screen;
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Keyboards\InlineKeyboard;

class DashboardFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        $status = $this->get('status', 'Active');

        return Screen::make("📊 User Dashboard\n\nCurrent Status: {$status}")
            ->inline(fn (InlineKeyboard $k) => $k
                ->action('Toggle Status', 'toggle')
                ->action('Open Settings', 'settings')
            )
            ->withNavigation(back: true, home: true);
    }

    #[Action('toggle')]
    public function toggleStatus(): void
    {
        $next = $this->get('status', 'Active') === 'Active' ? 'Paused' : 'Active';
        $this->set('status', $next);
        $this->refresh();
    }

    #[Action('settings')]
    public function openSettings(): void
    {
        $this->push(SettingsFlow::class);
    }
}
```
