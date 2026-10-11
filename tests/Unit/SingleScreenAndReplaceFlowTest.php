<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Flow\Attributes\SingleScreen;
use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Flow\InteractiveFlow;
use Tueen\Telegram\Flow\Screen;
use Tueen\Telegram\Flow\Storage\MemoryStateStore;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Testing\TelegramFake;
use Tueen\Telegram\Types\Update;

class TestScreenFlowOne extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Screen 1');
    }
}

#[SingleScreen]
class TestSingleScreenFlowTwo extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Screen 2 with SingleScreen');
    }
}

#[SingleScreen(clearPrevious: false)]
class TestSingleScreenFlowNoClear extends InteractiveFlow
{
    public function render(): Screen
    {
        return Screen::make('Screen with SingleScreen clearPrevious false');
    }
}

class TestPropertySingleScreenFlow extends InteractiveFlow
{
    protected bool $clearPreviousScreen = true;

    public function render(): Screen
    {
        return Screen::make('Screen with clearPreviousScreen property');
    }
}

class TestStandardLinearFlow extends Flow
{
    public bool $started = false;

    public function start(Update $update): void
    {
        $this->started = true;
        $this->bot->sendMessage(chatId: $this->chatId, text: 'Standard linear step');
    }
}

final class SingleScreenAndReplaceFlowTest extends TestCase
{
    private function createFakeBot(array $responses = []): TelegramFake
    {
        $defaultResponses = array_merge([
            'sendMessage' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Interactive'],
            'editMessageText' => ['message_id' => 500, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Interactive'],
            'deleteMessage' => true,
        ], $responses);

        $bot = Telegram::fake($defaultResponses);
        $bot->setFlowStore(new MemoryStateStore());
        return $bot;
    }

    private function makeUpdate(int $chatId = 12345, ?int $userId = 12345, string $text = '/start'): Update
    {
        return new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 1,
                'date' => time(),
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Tester'],
                'text' => $text,
            ],
        ]);
    }

    public function testReplaceFlowDeletesPreviousMessageAndStartsNewFlow(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 101, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        // 1. Start initial screen flow
        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertInstanceOf(TestScreenFlowOne::class, $flow1);
        $this->assertSame(101, $flow1->messageId);

        // 2. Call replaceFlow to replace with another flow
        $bot->fakeResponse('sendMessage', ['message_id' => 102, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $flow2 = $bot->replaceFlow(TestSingleScreenFlowTwo::class, $update);

        $this->assertInstanceOf(TestSingleScreenFlowTwo::class, $flow2);
        $this->assertSame(102, $flow2->messageId);

        // Assert deleteMessage was called for the previous messageId 101
        $bot->assertSent('deleteMessage', function (Request $request): bool {
            return ($request->parameters['chat_id'] ?? null) === 12345
                && ($request->parameters['message_id'] ?? null) === 101;
        });
    }

    public function testReplaceFlowHonorsDeletePreviousScreenFalse(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 201, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertSame(201, $flow1->messageId);

        // Replace with deletePreviousScreen: false
        $bot->fakeResponse('sendMessage', ['message_id' => 202, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $flow2 = $bot->replaceFlow(TestScreenFlowOne::class, $update, deletePreviousScreen: false);

        $this->assertInstanceOf(TestScreenFlowOne::class, $flow2);
        $this->assertSame(202, $flow2->messageId);

        // Assert deleteMessage was never sent
        $bot->assertNotSent('deleteMessage');
    }

    public function testReplaceFlowWithoutActiveFlowStartsCleanly(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 301, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        $flow = $bot->replaceFlow(TestScreenFlowOne::class, $update);

        $this->assertInstanceOf(TestScreenFlowOne::class, $flow);
        $this->assertSame(301, $flow->messageId);
        $bot->assertNotSent('deleteMessage');
    }

    public function testSingleScreenAttributeAutomaticallyTriggersDeleteMessageOnPreviousActiveScreen(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 401, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        // Start first flow which sets messageId 401 in session state
        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertSame(401, $flow1->messageId);

        // Start flow decorated with #[SingleScreen] via regular startFlow()
        $bot->fakeResponse('sendMessage', ['message_id' => 402, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $flow2 = $bot->startFlow(TestSingleScreenFlowTwo::class, $update);

        $this->assertInstanceOf(TestSingleScreenFlowTwo::class, $flow2);
        $this->assertSame(402, $flow2->messageId);

        // Assert deleteMessage was automatically triggered for message 401
        $bot->assertSent('deleteMessage', function (Request $request): bool {
            return ($request->parameters['chat_id'] ?? null) === 12345
                && ($request->parameters['message_id'] ?? null) === 401;
        });
    }

    public function testSingleScreenAttributeWithClearPreviousFalseDoesNotTriggerDelete(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 501, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertSame(501, $flow1->messageId);

        // Start flow decorated with #[SingleScreen(clearPrevious: false)]
        $bot->fakeResponse('sendMessage', ['message_id' => 502, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $flow2 = $bot->startFlow(TestSingleScreenFlowNoClear::class, $update);

        $this->assertInstanceOf(TestSingleScreenFlowNoClear::class, $flow2);
        $bot->assertNotSent('deleteMessage');
    }

    public function testPropertyClearPreviousScreenAutomaticallyTriggersDelete(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 601, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertSame(601, $flow1->messageId);

        // Start flow using protected bool $clearPreviousScreen = true
        $bot->fakeResponse('sendMessage', ['message_id' => 602, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $flow2 = $bot->startFlow(TestPropertySingleScreenFlow::class, $update);

        $this->assertInstanceOf(TestPropertySingleScreenFlow::class, $flow2);
        $bot->assertSent('deleteMessage', function (Request $request): bool {
            return ($request->parameters['chat_id'] ?? null) === 12345
                && ($request->parameters['message_id'] ?? null) === 601;
        });
    }

    public function testStandardLinearFlowsWithoutSingleScreenDoNotTriggerDeleteMessage(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 701, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertSame(701, $flow1->messageId);

        // Start linear flow that does not have #[SingleScreen] and extends Flow
        $bot->fakeResponse('sendMessage', ['message_id' => 702, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Linear']);
        $linearFlow = $bot->startFlow(TestStandardLinearFlow::class, $update);

        $this->assertInstanceOf(TestStandardLinearFlow::class, $linearFlow);
        $this->assertTrue($linearFlow->started);

        // deleteMessage must NOT be sent
        $bot->assertNotSent('deleteMessage');
    }

    public function testStartFlowClearPreviousScreenParameterOverrides(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 801, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        // 1. Flow decorated with #[SingleScreen], but override clearPreviousScreen: false passed
        $bot->startFlow(TestScreenFlowOne::class, $update);
        $bot->fakeResponse('sendMessage', ['message_id' => 802, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $bot->startFlow(TestSingleScreenFlowTwo::class, $update, clearPreviousScreen: false);
        $bot->assertNotSent('deleteMessage');

        // 2. Flow without #[SingleScreen], but override clearPreviousScreen: true passed
        $bot->startFlow(TestScreenFlowOne::class, $update);
        $bot->fakeResponse('sendMessage', ['message_id' => 803, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 3']);
        $bot->startFlow(TestScreenFlowOne::class, $update, clearPreviousScreen: true);
        $bot->assertSent('deleteMessage');
    }

    public function testDeletionFailureIsSilentlySuppressed(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 901, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
            // Simulate Telegram API failure for deleteMessage
            'deleteMessage' => new \RuntimeException('Bad Request: message to delete not found'),
        ]);
        $update = $this->makeUpdate();

        $flow1 = $bot->startFlow(TestScreenFlowOne::class, $update);
        $this->assertSame(901, $flow1->messageId);

        // Should NOT throw an exception despite deleteMessage failure
        $bot->fakeResponse('sendMessage', ['message_id' => 902, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $flow2 = $bot->startFlow(TestSingleScreenFlowTwo::class, $update);

        $this->assertInstanceOf(TestSingleScreenFlowTwo::class, $flow2);
        $this->assertSame(902, $flow2->messageId);
    }

    public function testFlowSessionReplaceFlowMethod(): void
    {
        $bot = $this->createFakeBot([
            'sendMessage' => ['message_id' => 950, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 1'],
        ]);
        $update = $this->makeUpdate();

        $bot->startFlow(TestScreenFlowOne::class, $update);

        $bot->fakeResponse('sendMessage', ['message_id' => 951, 'date' => time(), 'chat' => ['id' => 12345, 'type' => 'private'], 'text' => 'Screen 2']);
        $session = $bot->flow(chatId: 12345, userId: 12345, update: $update);
        $flow = $session->replaceFlow(TestSingleScreenFlowTwo::class);

        $this->assertInstanceOf(TestSingleScreenFlowTwo::class, $flow);
        $bot->assertSent('deleteMessage', function (Request $request): bool {
            return ($request->parameters['message_id'] ?? null) === 950;
        });
    }
}
