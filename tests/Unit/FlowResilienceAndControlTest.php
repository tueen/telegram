<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Config;
use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Flow\Attributes\AllowedUpdates;
use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Flow\FlowManager;
use Tueen\Telegram\Flow\FlowState;
use Tueen\Telegram\Flow\InteractiveFlow;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

// Test Flows
class ResilienceParentFlow extends InteractiveFlow
{
    public bool $resumed = false;
    public mixed $resumeData = null;

    public function onResume(mixed $result = null): void
    {
        $this->resumed = true;
        $this->resumeData = $result;
    }
}

class ResilienceRootFlow extends Flow
{
    public function start(Update $update): void
    {
        $this->set('atRoot', true);
    }
}

class MissingStepFlow extends Flow
{
    public bool $startCalled = false;

    public function start(Update $update): void
    {
        $this->startCalled = true;
    }

    public function stepOne(Update $update): void
    {
        $this->to('nonExistentStep');
    }
}

#[AllowedUpdates('message')]
class MessageOnlyFlow extends Flow
{
    public int $messageCount = 0;

    public function start(Update $update): void
    {
        $this->messageCount++;
    }
}

class CallbackOnlyFlow extends Flow
{
    protected array $allowedUpdates = [UpdateType::CALLBACK_QUERY];

    public int $callbackCount = 0;

    public function start(Update $update): void
    {
        $this->callbackCount++;
    }
}

class ExternalControlFlow extends Flow
{
    public function start(Update $update): void
    {
        $this->to('askName');
    }

    public function askName(Update $update): void
    {
        $this->set('name', $update->findAnyText());
        $this->to('askAge');
    }

    public function askAge(Update $update): void
    {
        $this->set('age', $update->findAnyText());
        $this->finish();
    }
}

class FlowResilienceAndControlTest extends TestCase
{
    private function createMessageUpdate(string $text, int $chatId = 12345, int $userId = 12345): Update
    {
        return new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Tester'],
                'text' => $text,
            ],
        ]);
    }

    private function createCallbackUpdate(string $data, int $chatId = 12345, int $userId = 12345): Update
    {
        return new Update([
            'update_id' => 2,
            'callback_query' => [
                'id' => 'cb_123',
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Tester'],
                'message' => [
                    'message_id' => 10,
                    'chat' => ['id' => $chatId, 'type' => 'private'],
                ],
                'data' => $data,
            ],
        ]);
    }

    public function testMissingFlowClassUnwindsStackToParentFlow(): void
    {
        $bot = Telegram::fake();
        $manager = $bot->flowManager;

        $sessionKey = FlowManager::resolveSessionKey(12345, 12345);

        // Simulate a state where current flowClass does not exist, but parent stack has ResilienceParentFlow
        $state = new FlowState(
            flowClass: 'NonExistent\\DeletedFlowClass',
            currentStep: 'someStep',
            flowStack: [
                [
                    'flowClass' => ResilienceParentFlow::class,
                    'currentStep' => 'start',
                    'data' => ['parentData' => 'yes'],
                    'messageId' => 999,
                    'breadcrumb' => 'Parent',
                ]
            ]
        );
        $manager->saveState($sessionKey, $state);

        $update = $this->createMessageUpdate('hello');
        $handled = $manager->handle($update, $bot);

        $this->assertTrue($handled);
        $activeState = $manager->getActiveState(12345, 12345);
        $this->assertNotNull($activeState);
        $this->assertSame(ResilienceParentFlow::class, $activeState->flowClass);
        $this->assertSame(['parentData' => 'yes'], $activeState->data);
    }

    public function testMissingFlowClassFallsBackToRootFlowWhenStackEmpty(): void
    {
        $bot = Telegram::fake();
        $manager = $bot->flowManager;
        $manager->setRootFlow(ResilienceRootFlow::class);

        $sessionKey = FlowManager::resolveSessionKey(12345, 12345);

        // State with deleted class and empty stack
        $state = new FlowState(
            flowClass: 'NonExistent\\DeletedFlowClass',
            currentStep: 'someStep',
            flowStack: []
        );
        $manager->saveState($sessionKey, $state);

        $update = $this->createMessageUpdate('hello');
        $handled = $manager->handle($update, $bot);

        $this->assertTrue($handled);
        $activeState = $manager->getActiveState(12345, 12345);
        $this->assertNotNull($activeState);
        $this->assertSame(ResilienceRootFlow::class, $activeState->flowClass);
        $this->assertTrue($activeState->data['atRoot'] ?? false);
    }

    public function testMissingFlowClassCleansUpWhenNoParentAndNoRootFlow(): void
    {
        $bot = Telegram::fake();
        $manager = $bot->flowManager;

        $sessionKey = FlowManager::resolveSessionKey(12345, 12345);

        $state = new FlowState(
            flowClass: 'NonExistent\\DeletedFlowClass',
            currentStep: 'someStep',
            flowStack: []
        );
        $manager->saveState($sessionKey, $state);

        $update = $this->createMessageUpdate('hello');
        $handled = $manager->handle($update, $bot);

        $this->assertFalse($handled);
        $this->assertNull($manager->getActiveState(12345, 12345));
    }

    public function testMissingStepFallsBackToPreviousStepOrStart(): void
    {
        $bot = Telegram::fake();
        $manager = $bot->flowManager;

        $sessionKey = FlowManager::resolveSessionKey(12345, 12345);

        // Flow whose current step does not exist, but history has 'start'
        $state = new FlowState(
            flowClass: MissingStepFlow::class,
            currentStep: 'nonExistentStep',
            history: ['start']
        );
        $manager->saveState($sessionKey, $state);

        $update = $this->createMessageUpdate('hello');
        $handled = $manager->handle($update, $bot);

        $this->assertTrue($handled);
        $activeState = $manager->getActiveState(12345, 12345);
        $this->assertNotNull($activeState);
        $this->assertSame('start', $activeState->currentStep);
    }

    public function testAllowedUpdatesViaAttribute(): void
    {
        $bot = Telegram::fake();
        $updateMsg = $this->createMessageUpdate('hello');
        $updateCb = $this->createCallbackUpdate('btn_click');

        $bot->startFlow(MessageOnlyFlow::class, $updateMsg);
        $this->assertTrue($bot->hasActiveFlow(12345, 12345));

        // Sending a callback_query should be disallowed and NOT captured by flow
        $handledCb = $bot->flowManager->handle($updateCb, $bot);
        $this->assertFalse($handledCb);

        // State remains active for subsequent allowed messages
        $this->assertTrue($bot->hasActiveFlow(12345, 12345));

        // Sending another message is allowed and handled
        $handledMsg = $bot->flowManager->handle($updateMsg, $bot);
        $this->assertTrue($handledMsg);
    }

    public function testAllowedUpdatesViaProperty(): void
    {
        $bot = Telegram::fake();
        $updateMsg = $this->createMessageUpdate('hello');
        $updateCb = $this->createCallbackUpdate('btn_click');

        $bot->startFlow(CallbackOnlyFlow::class, $updateCb);
        $this->assertTrue($bot->hasActiveFlow(12345, 12345));

        // Sending message should be rejected by flow
        $handledMsg = $bot->flowManager->handle($updateMsg, $bot);
        $this->assertFalse($handledMsg);

        // Sending callback_query is handled
        $handledCb = $bot->flowManager->handle($updateCb, $bot);
        $this->assertTrue($handledCb);
    }

    public function testExternalFlowSessionInspectionAndControl(): void
    {
        $bot = Telegram::fake();
        $startUpdate = $this->createMessageUpdate('/start');

        // Start flow
        $bot->startFlow(ExternalControlFlow::class, $startUpdate);

        // 1. External inspection via $bot->flow()
        $session = $bot->flow(12345, 12345);
        $this->assertTrue($session->isActive);
        $this->assertSame(ExternalControlFlow::class, $session->class);
        $this->assertSame('askName', $session->step);
        $this->assertSame('chat:12345', $session->sessionKey);

        // 2. Data manipulation
        $session->set('customKey', 'customValue');
        $this->assertTrue($session->has('customKey'));
        $this->assertSame('customValue', $session->get('customKey'));

        // 3. Step control via to()
        $session->to('askAge', ['name' => 'Alice']);
        $this->assertSame('askAge', $session->step);
        $this->assertSame('Alice', $session->get('name'));

        // 4. Navigation back via back()
        $session->back();
        $this->assertSame('askName', $session->step);

        // 5. Direct inspection methods on $bot
        $this->assertTrue($bot->hasActiveFlow(12345, 12345));
        $this->assertSame(ExternalControlFlow::class, $bot->getActiveFlowClass(12345, 12345));
        $this->assertInstanceOf(ExternalControlFlow::class, $bot->getActiveFlow(12345, 12345));

        // 6. Cancellation via $bot->cancelFlow()
        $cancelled = $bot->cancelFlow(12345, 12345);
        $this->assertTrue($cancelled);
        $this->assertFalse($bot->hasActiveFlow(12345, 12345));
    }

    public function testConfigFlowBuilderIntegration(): void
    {
        $config = Config::builder('123:TOKEN')
            ->withRootFlow(ResilienceRootFlow::class)
            ->withFlowAllowedUpdates(['message', 'callback_query'])
            ->build();

        $this->assertSame(ResilienceRootFlow::class, $config->rootFlow);
        $this->assertSame(ResilienceRootFlow::class, $config->defaultFlow);
        $this->assertSame(['message', 'callback_query'], $config->flowAllowedUpdates);

        $bot = new Telegram($config);
        $this->assertSame(ResilienceRootFlow::class, $bot->flowManager->rootFlow);
        $this->assertSame(['message', 'callback_query'], $bot->flowManager->defaultAllowedUpdates);
    }
}
