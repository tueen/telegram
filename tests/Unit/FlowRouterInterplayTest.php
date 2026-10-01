<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Routing\Route;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class FlowRouterInterplayTest extends TestCase
{
    protected function setUp(): void
    {
        SampleInterplayFlow::$stepOneCount = 0;
        SampleInterplayFlow::$onPriorityRouteCalled = false;
        VetoFlow::$vetoHandled = false;
    }

    private function createMessageUpdate(int $chatId, string $text, int $updateId = 1): Update
    {
        return new Update([
            'update_id' => $updateId,
            'message' => [
                'message_id' => 100 + $updateId,
                'date' => 1700000000,
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $chatId, 'is_bot' => false, 'first_name' => 'Tester'],
                'text' => $text,
            ],
        ]);
    }

    public function testPriorityRouteExecutesWhenFlowIsActive(): void
    {
        $bot = new Telegram('TEST_TOKEN');

        $priorityExecuted = false;
        $bot->onCommand('emergency', function () use (&$priorityExecuted) {
            $priorityExecuted = true;
        }, priority: true);

        $regularExecuted = false;
        $bot->onCommand('about', function () use (&$regularExecuted) {
            $regularExecuted = true;
        });

        // Start user in an active flow
        $startUpdate = $this->createMessageUpdate(chatId: 42, text: '/register', updateId: 1);
        $flow = $bot->startFlow(SampleInterplayFlow::class, $startUpdate);
        $this->assertSame('stepOne', $flow->state->currentStep);

        // 1. Regular command while in flow should be intercepted by Flow, NOT the router
        $aboutUpdate = $this->createMessageUpdate(chatId: 42, text: '/about', updateId: 2);
        $bot->dispatcher->dispatch($aboutUpdate, $bot, [], $bot->router, $bot->flowManager);
        $this->assertFalse($regularExecuted, 'Regular route must not execute when flow is active');
        $this->assertSame(1, SampleInterplayFlow::$stepOneCount);

        // 2. Priority route while in flow MUST execute and notify the flow
        $emergencyUpdate = $this->createMessageUpdate(chatId: 42, text: '/emergency', updateId: 3);
        $bot->dispatcher->dispatch($emergencyUpdate, $bot, [], $bot->router, $bot->flowManager);
        $this->assertTrue($priorityExecuted, 'Priority route must execute before flow');
        $this->assertTrue(SampleInterplayFlow::$onPriorityRouteCalled, 'Flow hook onPriorityRoute must be called');
        $this->assertSame(1, SampleInterplayFlow::$stepOneCount, 'Flow step must not consume priority update');
    }

    public function testFlowCanVetoPriorityRoute(): void
    {
        $bot = new Telegram('TEST_TOKEN');

        $priorityExecuted = false;
        $bot->onCommand('emergency', function () use (&$priorityExecuted) {
            $priorityExecuted = true;
        }, priority: true);

        // Start user in a flow that vetoes emergency
        $startUpdate = $this->createMessageUpdate(chatId: 99, text: '/register', updateId: 1);
        $flow = $bot->startFlow(VetoFlow::class, $startUpdate);

        $emergencyUpdate = $this->createMessageUpdate(chatId: 99, text: '/emergency', updateId: 2);
        $bot->dispatcher->dispatch($emergencyUpdate, $bot, [], $bot->router, $bot->flowManager);

        $this->assertFalse($priorityExecuted, 'Priority route must not execute if flow vetoes it');
        $this->assertTrue(VetoFlow::$vetoHandled, 'Flow must handle the update instead');
    }

    public function testFlowPassThroughFallsThroughToRouter(): void
    {
        $bot = new Telegram('TEST_TOKEN');

        $routerHandled = false;
        $bot->onMessage('/support/i', function () use (&$routerHandled) {
            $routerHandled = true;
        });

        // Start user in a flow
        $startUpdate = $this->createMessageUpdate(chatId: 55, text: '/start', updateId: 1);
        $flow = $bot->startFlow(PassThroughFlow::class, $startUpdate);

        // User types something that flow chooses to passThrough()
        $msgUpdate = $this->createMessageUpdate(chatId: 55, text: 'contact support please', updateId: 2);
        $bot->dispatcher->dispatch($msgUpdate, $bot, [], $bot->router, $bot->flowManager);

        $this->assertTrue($routerHandled, 'Router must handle update when flow calls passThrough()');
        // Flow state must still be maintained
        $this->assertSame('awaitInput', $flow->state->currentStep);
    }

    public function testFlowReturningFalseFallsThroughToRouter(): void
    {
        $bot = new Telegram('TEST_TOKEN');

        $routerHandled = null;
        $bot->onCommand('faq', function () use (&$routerHandled) {
            $routerHandled = 'faq_executed';
        });

        $startUpdate = $this->createMessageUpdate(chatId: 77, text: '/start', updateId: 1);
        $flow = $bot->startFlow(ReturnFalseFlow::class, $startUpdate);

        $faqUpdate = $this->createMessageUpdate(chatId: 77, text: '/faq', updateId: 2);
        $bot->dispatcher->dispatch($faqUpdate, $bot, [], $bot->router, $bot->flowManager);

        $this->assertSame('faq_executed', $routerHandled, 'Router must execute when flow step returns false');
    }

    public function testAttributeControllerPriorityRoutes(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $controller = new class {
            public bool $alertExecuted = false;

            #[OnCommand('alert', priority: true)]
            public function alert(): void
            {
                $this->alertExecuted = true;
            }
        };

        $bot->router->registerController($controller);

        $startUpdate = $this->createMessageUpdate(chatId: 88, text: '/start', updateId: 1);
        $bot->startFlow(SampleInterplayFlow::class, $startUpdate);

        $alertUpdate = $this->createMessageUpdate(chatId: 88, text: '/alert', updateId: 2);
        $bot->dispatcher->dispatch($alertUpdate, $bot, [], $bot->router, $bot->flowManager);

        $this->assertTrue($controller->alertExecuted, 'Attribute-registered priority command must execute over active flow');
    }
}

class SampleInterplayFlow extends Flow
{
    public static int $stepOneCount = 0;
    public static bool $onPriorityRouteCalled = false;

    public function start(Update $update): void
    {
        $this->to('stepOne');
    }

    public function stepOne(Update $update): void
    {
        self::$stepOneCount++;
    }

    public function onPriorityRoute(Route $route, Update $update): void
    {
        self::$onPriorityRouteCalled = true;
    }
}

class VetoFlow extends Flow
{
    public static bool $vetoHandled = false;

    public function start(Update $update): void
    {
        $this->to('step');
    }

    public function step(Update $update): void
    {
        self::$vetoHandled = true;
    }

    public function allowsPriorityRoute(Route $route, Update $update): bool
    {
        // Veto emergency command
        if ($route->pattern === 'emergency') {
            return false;
        }

        return true;
    }
}

class PassThroughFlow extends Flow
{
    public function start(Update $update): void
    {
        $this->to('awaitInput');
    }

    public function awaitInput(Update $update): void
    {
        $text = $update->message?->text ?? '';
        if (str_contains($text, 'support')) {
            $this->passThrough();
            return;
        }
    }
}

class ReturnFalseFlow extends Flow
{
    public function start(Update $update): void
    {
        $this->to('step');
    }

    public function step(Update $update): bool
    {
        if ($update->message?->text === '/faq') {
            return false; // Fall through
        }

        return true;
    }
}
