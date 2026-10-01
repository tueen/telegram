<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Flow\Attributes\Breadcrumb;
use Tueen\Telegram\Flow\Attributes\Debounce;
use Tueen\Telegram\Flow\Attributes\RequireMember;
use Tueen\Telegram\Flow\InteractiveFlow;
use Tueen\Telegram\Flow\Navigation;
use Tueen\Telegram\Flow\Screen;
use Tueen\Telegram\Flow\Storage\MemoryStateStore;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Testing\TelegramFake;
use Tueen\Telegram\Types\Update;

// Test Flow Implementations
class TestInteractiveMenuFlow extends InteractiveFlow
{
    public bool $created = false;
    public bool $started = false;
    public ?string $resumedWith = null;
    public ?string $actionClicked = null;
    public ?int $selectedItemId = null;
    public bool $unhandledTriggered = false;

    public function onCreate(): void
    {
        $this->created = true;
    }

    public function onStart(): void
    {
        $this->started = true;
    }

    public function onResume(mixed $result = null): void
    {
        $this->resumedWith = is_string($result) ? $result : null;
    }

    public function render(): Screen
    {
        return Screen::make('Interactive Main Menu')
            ->inline(fn (InlineKeyboard $k) => $k
                ->action('Option A', 'opt_a')
                ->action('Option B', 'opt_b')
                ->action('Item 42', 'item_42')
                ->action('Open Subflow', 'open_subflow')
            )
            ->withNavigation(back: true, home: true);
    }

    #[Action('opt_a')]
    public function handleOptionA(): void
    {
        $this->actionClicked = 'opt_a';
        $this->set('choice', 'A');
        $this->refresh();
    }

    #[Action('item_{id}')]
    public function handleItem(int $id): void
    {
        $this->selectedItemId = $id;
        $this->refresh();
    }

    #[Action('open_subflow')]
    public function openSubflow(): void
    {
        $this->push(TestInteractiveSubFlow::class, ['parentData' => 'hello']);
    }

    public function onUnhandled(Update $update): void
    {
        $this->unhandledTriggered = true;
        parent::onUnhandled($update);
    }
}

class TestInteractiveSubFlow extends InteractiveFlow
{
    public bool $subCreated = false;
    public bool $subPaused = false;

    public function onCreate(): void
    {
        $this->subCreated = true;
    }

    public function onPause(): void
    {
        $this->subPaused = true;
    }

    public function render(): Screen
    {
        return Screen::make('Subflow Screen')
            ->inline(fn (InlineKeyboard $k) => $k
                ->action('Done Subflow', 'done_subflow')
            )
            ->withNavigation(back: true, home: true);
    }

    #[Action('done_subflow')]
    public function done(): void
    {
        $this->pop('result_from_sub');
    }
}

class TestLockedCheckoutFlow extends InteractiveFlow
{
    public bool $lockedAttemptLogged = false;

    public function canInterrupt(Update $update): bool
    {
        // Forbid exiting during checkout
        return false;
    }

    public function render(): Screen
    {
        return Screen::make('Checkout in progress - cannot exit')
            ->withNavigation(back: false, home: false);
    }

    public function onUnhandled(Update $update): void
    {
        $this->lockedAttemptLogged = true;
    }
}

class TestRootFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Root Home Screen');
    }
}

class TestAlertAndToastFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Alert Menu')
            ->inline(fn (InlineKeyboard $k) => $k
                ->action('Trigger Alert', 'do_alert')
                ->action('Trigger Toast', 'do_toast')
                ->action('Resend Bottom', 'do_resend')
                ->action('Ask Confirm', 'do_confirm')
            );
    }

    #[Action('do_alert')]
    public function handleAlert(): void
    {
        $this->alert('Modal Alert Message');
    }

    #[Action('do_toast')]
    public function handleToast(): void
    {
        $this->toast('Bottom Toast Message');
    }

    #[Action('do_resend')]
    public function handleResend(): void
    {
        $this->resendAtBottom(deleteOld: true);
    }

    #[Action('do_confirm')]
    public function handleConfirm(): void
    {
        $this->confirm('Are you sure?', 'confirmed_action');
    }

    #[Action('confirmed_action')]
    public function handleConfirmed(): void
    {
        $this->toast('Confirmed!');
    }
}

class TestMediaFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make()
            ->photo('https://example.com/photo.jpg', caption: 'Photo Caption')
            ->inline(fn (InlineKeyboard $k) => $k->action('Change Caption', 'change_cap'));
    }

    #[Action('change_cap')]
    public function changeCaption(): void
    {
        $this->renderScreen(
            Screen::make()
                ->photo('https://example.com/photo.jpg', caption: 'Updated Caption')
                ->inline(fn (InlineKeyboard $k) => $k->action('Updated', 'noop'))
        );
    }
}

class TestChecklistFlow extends InteractiveFlow
{
    public function onStart(): void
    {
        if (!$this->has('interests')) {
            $this->set('interests', ['tech']);
        }
    }

    public function render(): Screen
    {
        $selected = $this->get('interests', ['tech']);

        return Screen::make('Checklist Menu')
            ->withChecklist(
                items: ['sports' => 'Sports', 'tech' => 'Tech', 'crypto' => 'Crypto'],
                selected: $selected,
                toggleActionPattern: 'toggle_{key}',
                confirmAction: 'save_checklist'
            );
    }

    #[Action('toggle_{key}')]
    public function handleToggle(string $key): void
    {
        $this->toggleChecklist('interests', $key);
    }
}

class TestDebounceFlow extends InteractiveFlow
{
    public int $counter = 0;

    public function render(): Screen
    {
        return Screen::make('Counter: ' . $this->counter)
            ->inline(fn (InlineKeyboard $k) => $k->action('Increment', 'inc'));
    }

    #[Action('inc')]
    #[Debounce(seconds: 1.0, notice: 'Too fast!')]
    public function increment(): void
    {
        $this->counter++;
        $this->refresh();
    }
}

#[Breadcrumb('Parent Title')]
class TestBreadcrumbParentFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Parent Body')
            ->withBreadcrumbs()
            ->inline(fn (InlineKeyboard $k) => $k->action('Go Child', 'go_child'));
    }

    #[Action('go_child')]
    public function goChild(): void
    {
        $this->push(TestBreadcrumbChildFlow::class);
    }
}

#[Breadcrumb('Child Title')]
class TestBreadcrumbChildFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Child Body')
            ->withBreadcrumbs();
    }
}

class TestGuardedFlow extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Guarded Menu')
            ->inline(fn (InlineKeyboard $k) => $k->action('Secret Area', 'secret'));
    }

    #[Action('secret')]
    #[RequireMember('@vip_club', fallbackMessage: 'Join @vip_club first!')]
    public function secret(): void
    {
        $this->toast('Welcome to the VIP area!');
    }
}

final class InteractiveFlowTest extends TestCase
{
    private function createFakeBot(array $responses = []): TelegramFake
    {
        $defaultResponses = array_merge([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Interactive'],
            'editMessageText' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Interactive'],
            'answerCallbackQuery' => true,
        ], $responses);

        $bot = Telegram::fake($defaultResponses);
        $bot->setFlowStore(new MemoryStateStore());
        return $bot;
    }

    public function testScreenBuildingAndNavigation(): void
    {
        $screen = Screen::make('Hello World')
            ->parseMode(ParseMode::HTML)
            ->inline(fn (InlineKeyboard $k) => $k
                ->action('Click Me', 'btn_click')
            )
            ->withNavigation(back: true, home: true);

        $this->assertSame('Hello World', $screen->text);
        $this->assertSame(ParseMode::HTML, $screen->parseMode);
        $this->assertTrue($screen->editIfPossible);

        $keyboard = $screen->buildKeyboard();
        $this->assertNotNull($keyboard);

        $array = $keyboard->toArray();
        $this->assertArrayHasKey('inline_keyboard', $array);

        // First row should have 'Click Me'
        $this->assertSame('Click Me', $array['inline_keyboard'][0][0]['text']);
        // Navigation row should have 🔙 and 🏠
        $this->assertSame('🔙', $array['inline_keyboard'][1][0]['text']);
        $this->assertSame(Navigation::BACK_ACTION, $array['inline_keyboard'][1][0]['callback_data']);
        $this->assertSame('🏠', $array['inline_keyboard'][1][1]['text']);
        $this->assertSame(Navigation::HOME_ACTION, $array['inline_keyboard'][1][1]['callback_data']);
    }

    public function testInitialRenderSendsMessageAndCapturesMessageId(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 999, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Interactive Main Menu'],
        ]);

        $update = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/menu',
            ],
        ]);

        /** @var TestInteractiveMenuFlow $flow */
        $flow = $bot->startFlow(TestInteractiveMenuFlow::class, $update);

        $this->assertTrue($flow->created);
        $this->assertTrue($flow->started);
        $this->assertSame(999, $flow->messageId);
        $this->assertSame(999, $flow->state->messageId);

        $bot->assertSent('sendMessage', fn (array $params) => $params['chat_id'] === 12345 && str_contains($params['text'], 'Interactive Main Menu'));
    }

    public function testActionDispatchingAndInPlaceEdit(): void
    {
        $bot = $this->createFakeBot();

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/menu',
            ],
        ]);

        $bot->startFlow(TestInteractiveMenuFlow::class, $initialUpdate);

        // Click Option A via callback query
        $callbackUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_123',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'opt_a',
            ],
        ]);

        $handled = $bot->flowManager->handle($callbackUpdate, $bot);
        $this->assertTrue($handled);

        // Callback query answered and message edited in-place
        $bot->assertSent('answerCallbackQuery');
        $bot->assertSent('editMessageText', fn (array $params) => $params['message_id'] === 500);

        $activeState = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame('A', $activeState->data['choice'] ?? null);
    }

    public function testParameterizedActionPattern(): void
    {
        $bot = $this->createFakeBot();

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/menu',
            ],
        ]);

        $bot->startFlow(TestInteractiveMenuFlow::class, $initialUpdate);

        // Click parameterized button item_42
        $callbackUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_item',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'item_42',
            ],
        ]);

        $bot->flowManager->handle($callbackUpdate, $bot);

        // Assert editMessageText was called
        $bot->assertSent('editMessageText', fn (array $params) => $params['message_id'] === 500);
    }

    public function testPushAndPopHierarchicalNavigationStack(): void
    {
        $bot = $this->createFakeBot([
            'editMessageText' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Subflow Screen'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/menu',
            ],
        ]);

        $bot->startFlow(TestInteractiveMenuFlow::class, $initialUpdate);

        // Trigger push to subflow
        $pushUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_sub',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'open_subflow',
            ],
        ]);

        $bot->flowManager->handle($pushUpdate, $bot);

        $state = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame(TestInteractiveSubFlow::class, $state->flowClass);
        $this->assertCount(1, $state->flowStack);
        $this->assertSame(TestInteractiveMenuFlow::class, $state->flowStack[0]['flowClass']);
        $this->assertSame(500, $state->messageId);

        // Now inside subflow, click Done Subflow which calls pop('result_from_sub')
        $popUpdate = new Update([
            'update_id' => 3,
            'callback_query' => [
                'id' => 'cb_done',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'done_subflow',
            ],
        ]);

        $bot->flowManager->handle($popUpdate, $bot);

        // Flow should have returned to TestInteractiveMenuFlow
        $resumedState = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame(TestInteractiveMenuFlow::class, $resumedState->flowClass);
        $this->assertEmpty($resumedState->flowStack);
        $this->assertSame(500, $resumedState->messageId);
    }

    public function testNavigationBackAndHomeViaEmojiText(): void
    {
        $bot = $this->createFakeBot();

        $bot->setRootFlow(TestRootFlow::class);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/menu',
            ],
        ]);

        $bot->startFlow(TestInteractiveMenuFlow::class, $initialUpdate);

        // Send text 🏠 to trigger home navigation
        $homeTextUpdate = new Update([
            'update_id' => 2,
            'message' => [
                'message_id' => 11,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '🏠',
            ],
        ]);

        $bot->flowManager->handle($homeTextUpdate, $bot);

        $state = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame(TestRootFlow::class, $state->flowClass);
    }

    public function testCanInterruptPreventsExitingWhenFalse(): void
    {
        $bot = $this->createFakeBot();

        $bot->setRootFlow(TestRootFlow::class);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/checkout',
            ],
        ]);

        $bot->startFlow(TestLockedCheckoutFlow::class, $initialUpdate);

        // User attempts to send /start
        $startUpdate = new Update([
            'update_id' => 2,
            'message' => [
                'message_id' => 11,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/start',
            ],
        ]);

        $handled = $bot->flowManager->handle($startUpdate, $bot);
        $this->assertTrue($handled);

        // Flow should still be TestLockedCheckoutFlow, NOT reset to root flow!
        $state = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame(TestLockedCheckoutFlow::class, $state->flowClass);
    }

    public function testUnhandledInputTriggersHook(): void
    {
        $bot = $this->createFakeBot();

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/menu',
            ],
        ]);

        $bot->startFlow(TestInteractiveMenuFlow::class, $initialUpdate);

        // Send unexpected random message
        $randomUpdate = new Update([
            'update_id' => 2,
            'message' => [
                'message_id' => 11,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => 'some random text',
            ],
        ]);

        $handled = $bot->flowManager->handle($randomUpdate, $bot);
        $this->assertTrue($handled);

        // In-place refresh occurred
        $bot->assertSent('editMessageText');
    }

    public function testAlertAndToastDispatching(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Alert Menu'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/start',
            ],
        ]);

        $bot->startFlow(TestAlertAndToastFlow::class, $initialUpdate);

        // Click Trigger Alert
        $alertUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_alert_1',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'do_alert',
            ],
        ]);

        $bot->flowManager->handle($alertUpdate, $bot);

        $bot->assertSent('answerCallbackQuery', fn (array $params) => 
            $params['callback_query_id'] === 'cb_alert_1' &&
            $params['text'] === 'Modal Alert Message' &&
            $params['show_alert'] === true
        );

        // Click Trigger Toast
        $toastUpdate = new Update([
            'update_id' => 3,
            'callback_query' => [
                'id' => 'cb_toast_2',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'do_toast',
            ],
        ]);

        $bot->flowManager->handle($toastUpdate, $bot);

        $bot->assertSent('answerCallbackQuery', fn (array $params) => 
            $params['callback_query_id'] === 'cb_toast_2' &&
            $params['text'] === 'Bottom Toast Message' &&
            $params['show_alert'] === false
        );
    }

    public function testResendAtBottomDeletesOldAndSendsNew(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Alert Menu'],
            'deleteMessage' => true,
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/start',
            ],
        ]);

        $bot->startFlow(TestAlertAndToastFlow::class, $initialUpdate);

        // Next sendMessage stub for the resend
        $bot->fakeResponse('sendMessage', ['message_id' => 777, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Alert Menu']);

        // Trigger resend
        $resendUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_resend',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'do_resend',
            ],
        ]);

        $bot->flowManager->handle($resendUpdate, $bot);

        // Verify deleteMessage called for old message 500
        $bot->assertSent('deleteMessage', fn (array $params) => $params['message_id'] === 500);

        // State messageId updated to 777
        $state = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame(777, $state->messageId);
    }

    public function testPaginationRowGeneration(): void
    {
        $screen = Screen::make('Items List')
            ->withPagination(currentPage: 2, totalPages: 5, actionPattern: 'page_{page}')
            ->withNavigation(back: true, home: false);

        $keyboard = $screen->buildKeyboard();
        $this->assertNotNull($keyboard);

        $array = $keyboard->toArray();
        $rows = $array['inline_keyboard'];

        // Pagination row should be first row
        $this->assertCount(3, $rows[0]);
        $this->assertSame('◀️', $rows[0][0]['text']);
        $this->assertSame('page_1', $rows[0][0]['callback_data']);
        $this->assertSame('2/5', $rows[0][1]['text']);
        $this->assertSame('noop', $rows[0][1]['callback_data']);
        $this->assertSame('▶️', $rows[0][2]['text']);
        $this->assertSame('page_3', $rows[0][2]['callback_data']);

        // Navigation row should be second row
        $this->assertSame('🔙', $rows[1][0]['text']);
    }

    public function testConfirmationScreen(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Menu'],
            'editMessageText' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Are you sure?'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/start',
            ],
        ]);

        $bot->startFlow(TestAlertAndToastFlow::class, $initialUpdate);

        // Click Ask Confirm
        $confirmUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_confirm',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'do_confirm',
            ],
        ]);

        $bot->flowManager->handle($confirmUpdate, $bot);

        // Assert editMessageText was called with confirm prompt
        $bot->assertSent('editMessageText', fn (array $params) => 
            $params['message_id'] === 500 &&
            $params['text'] === 'Are you sure?'
        );
    }

    public function testMediaScreenRenderingAndCaptionEditing(): void
    {
        $bot = $this->createFakeBot([
            'sendPhoto' => ['message_id' => 888, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'caption' => 'Photo Caption'],
            'editMessageCaption' => ['message_id' => 888, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'caption' => 'Updated Caption'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/photo',
            ],
        ]);

        $bot->startFlow(TestMediaFlow::class, $initialUpdate);

        $bot->assertSent('sendPhoto', fn (array $params) => 
            $params['chat_id'] === 12345 &&
            $params['photo'] === 'https://example.com/photo.jpg' &&
            $params['caption'] === 'Photo Caption'
        );

        $state = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertSame(888, $state->messageId);

        // Now trigger caption change
        $changeCapUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_cap',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 888, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'change_cap',
            ],
        ]);

        $bot->flowManager->handle($changeCapUpdate, $bot);

        $bot->assertSent('editMessageCaption', fn (array $params) => 
            $params['message_id'] === 888 &&
            $params['caption'] === 'Updated Caption'
        );
    }

    public function testChecklistGridAndToggle(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Checklist Menu'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/checklist',
            ],
        ]);

        $bot->startFlow(TestChecklistFlow::class, $initialUpdate);

        // Click toggle_sports
        $toggleUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_sports',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'toggle_sports',
            ],
        ]);

        $bot->flowManager->handle($toggleUpdate, $bot);

        $state = $bot->flowManager->getActiveState(12345, 12345);
        $this->assertContains('sports', $state->data['interests']);
        $this->assertContains('tech', $state->data['interests']);
    }

    public function testActionDebounce(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Counter: 0'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/counter',
            ],
        ]);

        /** @var TestDebounceFlow $flow */
        $flow = $bot->startFlow(TestDebounceFlow::class, $initialUpdate);
        $this->assertSame(0, $flow->counter);

        // First click
        $click1 = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_inc_1',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'inc',
            ],
        ]);

        $bot->flowManager->handle($click1, $bot);

        // Second click immediately (< 1s)
        $click2 = new Update([
            'update_id' => 3,
            'callback_query' => [
                'id' => 'cb_inc_2',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'inc',
            ],
        ]);

        $bot->flowManager->handle($click2, $bot);

        // Second click should be answered with debounce notice 'Too fast!'
        $bot->assertSent('answerCallbackQuery', fn (array $params) => 
            $params['callback_query_id'] === 'cb_inc_2' &&
            $params['text'] === 'Too fast!'
        );
    }

    public function testBreadcrumbsTrail(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Parent'],
            'editMessageText' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Child'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/bc',
            ],
        ]);

        $bot->startFlow(TestBreadcrumbParentFlow::class, $initialUpdate);

        $bot->assertSent('sendMessage', fn (array $params) => 
            str_contains($params['text'], 'Parent Title') &&
            str_contains($params['text'], 'Parent Body')
        );

        // Click go_child
        $childUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_child',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'go_child',
            ],
        ]);

        $bot->flowManager->handle($childUpdate, $bot);

        // Child should have rendered both Parent Title and Child Title in trail
        $bot->assertSent('editMessageText', fn (array $params) => 
            str_contains($params['text'], 'Parent Title › Child Title') &&
            str_contains($params['text'], 'Child Body')
        );
    }

    public function testStepWizardProgress(): void
    {
        $screenDots = Screen::make('Body')->withStepper(2, 4, 'dots');
        $this->assertStringContainsString('● ● ○ ○ Step 2 of 4', $screenDots->text);

        $screenBar = Screen::make('Body')->withStepper(2, 4, 'bar');
        $this->assertStringContainsString('[████░░░░] Step 2 of 4', $screenBar->text);

        $screenNumbers = Screen::make('Body')->withStepper(2, 4, 'numbers');
        $this->assertStringContainsString('(2/4) Step 2 of 4', $screenNumbers->text);
    }

    public function testChannelMembershipGuard(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Guarded Menu'],
            'getChatMember' => ['status' => 'left'],
        ]);

        $initialUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'text' => '/guarded',
            ],
        ]);

        $bot->startFlow(TestGuardedFlow::class, $initialUpdate);

        // Click secret action while user has status 'left'
        $secretUpdate = new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_secret',
                'from' => ['id' => 12345, 'is_bot' => false, 'first_name' => 'John'],
                'message' => ['message_id' => 500, 'chat' => ['id' => 12345, 'type' => 'private']],
                'data' => 'secret',
            ],
        ]);

        $bot->flowManager->handle($secretUpdate, $bot);

        // Join screen was rendered with fallback message
        $bot->assertSent('editMessageText', fn (array $params) => 
            str_contains($params['text'], 'Join @vip_club first!')
        );
    }
}
