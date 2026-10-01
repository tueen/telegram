<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class RunningModesTest extends TestCase
{
    public function testWebhookModeParsesPayloadAndInvokesHandler(): void
    {
        $payload = json_encode([
            'update_id' => 100,
            'message' => [
                'message_id' => 1,
                'date' => 1700000000,
                'chat' => ['id' => 123, 'type' => 'private'],
                'text' => 'hello',
            ],
        ]);

        $mode = new WebhookMode(rawInput: $payload);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode($mode);

        $handled = false;
        $receivedUpdate = null;

        $update = $bot->run(function (Update $up) use (&$handled, &$receivedUpdate) {
            $handled = true;
            $receivedUpdate = $up;
        });

        $this->assertTrue($handled);
        $this->assertInstanceOf(Update::class, $update);
        $this->assertSame($update, $receivedUpdate);
        $this->assertSame(100, $update->updateId);
        $this->assertSame('hello', $update->message?->text);
    }

    public function testWebhookModeSecretTokenValidation(): void
    {
        $payload = json_encode(['update_id' => 200]);

        // 1. Success when secret token matches header
        $validMode = new WebhookMode(
            secretToken: 'secret_123',
            rawInput: $payload,
            headers: ['X-Telegram-Bot-Api-Secret-Token' => 'secret_123']
        );
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode($validMode);

        $this->assertNull($bot->update);
        $update = $bot->run();
        $this->assertSame(200, $update->updateId);
        $this->assertSame($update, $bot->update);

        // 2. Fails when secret token does not match
        $invalidMode = new WebhookMode(
            secretToken: 'secret_123',
            rawInput: $payload,
            headers: ['X-Telegram-Bot-Api-Secret-Token' => 'wrong_token']
        );
        $bot->setRunningMode($invalidMode);

        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('Invalid or missing Telegram webhook secret token.');
        $bot->run();
    }

    public function testRunPassesUpdateAndBotInstance(): void
    {
        $payload = json_encode(['update_id' => 300]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload));

        $capturedUpdate = null;
        $capturedBot = null;

        $bot->run(function (Update $update, Telegram $passedBot) use (&$capturedUpdate, &$capturedBot) {
            $capturedUpdate = $update;
            $capturedBot = $passedBot;
        });

        $this->assertInstanceOf(Update::class, $capturedUpdate);
        $this->assertSame(300, $capturedUpdate->updateId);
        $this->assertSame($bot, $capturedBot);
        $this->assertSame($capturedUpdate, $bot->update);
    }

    public function testRunInvokableClassString(): void
    {
        $payload = json_encode(['update_id' => 400]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload));

        TestInvokableUpdateHandler::$invoked = false;
        TestInvokableUpdateHandler::$receivedUpdate = null;
        TestInvokableUpdateHandler::$receivedBot = null;

        $bot->run(TestInvokableUpdateHandler::class);

        $this->assertTrue(TestInvokableUpdateHandler::$invoked);
        $this->assertSame(400, TestInvokableUpdateHandler::$receivedUpdate?->updateId);
        $this->assertSame($bot, TestInvokableUpdateHandler::$receivedBot);
        $this->assertSame($bot->update, TestInvokableUpdateHandler::$receivedUpdate);
    }

    public function testRunMultipleHandlersInArrayAndVariadic(): void
    {
        $payload = json_encode(['update_id' => 500]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload));

        $order = [];

        $h1 = function (Update $u, Telegram $b) use (&$order) {
            $order[] = 'h1';
        };
        $h2 = function (Update $u, Telegram $b) use (&$order) {
            $order[] = 'h2';
        };

        // Test variadic
        $bot->run($h1, $h2);
        $this->assertSame(['h1', 'h2'], $order);

        // Test array
        $order = [];
        $bot->run([$h1, $h2]);
        $this->assertSame(['h1', 'h2'], $order);
    }

    public function testHandleRegistrationAndPropagationStop(): void
    {
        $payload = json_encode(['update_id' => 600]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload));

        $called = [];

        $bot
            ->handle(function (Update $u, Telegram $b) use (&$called) {
                $called[] = 1;
                return false; // Stop propagation
            })
            ->handle(function (Update $u, Telegram $b) use (&$called) {
                $called[] = 2;
            })
            ->run();

        $this->assertSame([1], $called);
    }

    public function testPollingModeFetchesUpdatesAndTracksOffset(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        // First batch: updates 100, 101
        // Second batch: update 102
        $mockHttp->expects($this->exactly(2))
            ->method('send')
            ->willReturnOnConsecutiveCalls(
                new Response(200, [
                    'ok' => true,
                    'result' => [
                        [
                            'update_id' => 100,
                            'message' => [
                                'message_id' => 1,
                                'date' => 1700000000,
                                'chat' => ['id' => 1, 'type' => 'private'],
                                'text' => 'msg 1',
                            ],
                        ],
                        [
                            'update_id' => 101,
                            'message' => [
                                'message_id' => 2,
                                'date' => 1700000000,
                                'chat' => ['id' => 1, 'type' => 'private'],
                                'text' => 'msg 2',
                            ],
                        ],
                    ],
                ]),
                new Response(200, [
                    'ok' => true,
                    'result' => [
                        [
                            'update_id' => 102,
                            'message' => [
                                'message_id' => 3,
                                'date' => 1700000000,
                                'chat' => ['id' => 1, 'type' => 'private'],
                                'text' => 'msg 3',
                            ],
                        ],
                    ],
                ])
            );

        $config = Telegram::create('TEST_TOKEN')
            ->withHttpClient($mockHttp)
            ->build();

        $bot = new Telegram($config);

        $polling = new PollingMode(timeout: 10, limit: 10);
        $bot->setRunningMode($polling);

        $receivedUpdates = [];

        // Run polling until we get update 102, then stop
        $bot->run(function (Update $update, Telegram $passedBot) use (&$receivedUpdates, $polling, $bot) {
            $receivedUpdates[] = $update->updateId;
            $this->assertSame($update, $passedBot->update);
            $this->assertSame($bot, $passedBot);
            if ($update->updateId === 102) {
                $polling->stop();
            }
        });

        $this->assertSame([100, 101, 102], $receivedUpdates);
        // Next expected offset should be 103
        $this->assertSame(103, $polling->offset);
    }

    public function testPollingModeWithCustomProcessDispatcher(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('send')->willReturn(new Response(200, [
            'ok' => true,
            'result' => [
                ['update_id' => 1000],
            ],
        ]));

        $config = Telegram::create('TEST_TOKEN')->withHttpClient($mockHttp)->build();
        $bot = new Telegram($config);

        $polling = new PollingMode(timeout: 1, limit: 1);
        $dispatched = false;

        $polling->setProcessDispatcher(function (Update $update, Telegram $b, callable $next) use (&$dispatched, $polling) {
            $dispatched = true;
            $next();
            $polling->stop();
        });

        $bot->setRunningMode($polling);

        $handlerExecuted = false;
        $bot->run(function (Update $update, Telegram $passedBot) use (&$handlerExecuted) {
            $handlerExecuted = true;
            $this->assertSame(1000, $update->updateId);
            $this->assertSame($update, $passedBot->update);
        });

        $this->assertTrue($dispatched);
        $this->assertTrue($handlerExecuted);
    }

    public function testConfigBuilderWithRunningMode(): void
    {
        $mode = new PollingMode(timeout: 15);
        $config = Telegram::create('TEST_TOKEN')
            ->withRunningMode($mode)
            ->build();

        $this->assertSame($mode, $config->runningMode);

        $bot = new Telegram($config);
        $this->assertSame($mode, $bot->runningMode);
    }
}

class TestInvokableUpdateHandler
{
    public static bool $invoked = false;
    public static ?Update $receivedUpdate = null;
    public static ?Telegram $receivedBot = null;

    public function __invoke(Update $update, Telegram $bot): void
    {
        self::$invoked = true;
        self::$receivedUpdate = $update;
        self::$receivedBot = $bot;
    }
}
