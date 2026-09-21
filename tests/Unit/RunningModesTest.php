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
        $telegram = new Telegram('TEST_TOKEN');
        $telegram->setRunningMode($mode);

        $handled = false;
        $receivedUpdate = null;

        $update = $telegram->run(function (Update $up) use (&$handled, &$receivedUpdate) {
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
        $telegram = new Telegram('TEST_TOKEN');
        $telegram->setRunningMode($validMode);
        $update = $telegram->getUpdate();
        $this->assertSame(200, $update->updateId);

        // 2. Fails when secret token does not match
        $invalidMode = new WebhookMode(
            secretToken: 'secret_123',
            rawInput: $payload,
            headers: ['X-Telegram-Bot-Api-Secret-Token' => 'wrong_token']
        );
        $telegram->setRunningMode($invalidMode);

        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('Invalid or missing Telegram webhook secret token.');
        $telegram->getUpdate();
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

        $telegram = new Telegram($config);

        $polling = new PollingMode(timeout: 10, limit: 10);
        $telegram->setRunningMode($polling);

        $receivedUpdates = [];

        // Run polling until we get update 102, then stop
        $polling->processUpdate($telegram, function (Update $update) use (&$receivedUpdates, $polling) {
            $receivedUpdates[] = $update->updateId;
            if ($update->updateId === 102) {
                $polling->stop();
            }
        });

        $this->assertSame([100, 101, 102], $receivedUpdates);
        // Next expected offset should be 103
        $this->assertSame(103, $polling->getOffset());
    }

    public function testConfigBuilderWithRunningMode(): void
    {
        $mode = new PollingMode(timeout: 15);
        $config = Telegram::create('TEST_TOKEN')
            ->withRunningMode($mode)
            ->build();

        $this->assertSame($mode, $config->runningMode);

        $telegram = new Telegram($config);
        $this->assertSame($mode, $telegram->getRunningMode());
    }
}
