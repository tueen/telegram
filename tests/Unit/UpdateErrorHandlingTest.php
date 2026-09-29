<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tueen\Telegram\App;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Running\PollingMode;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;

class CustomDomainException extends RuntimeException {}
class AnotherDomainException extends RuntimeException {}

class UpdateErrorHandlingTest extends TestCase
{
    public function testUniversalCatchHandlesException(): void
    {
        $payload = json_encode(['update_id' => 100]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload, safeExceptions: false));

        $caught = false;
        $caughtError = null;
        $caughtUpdate = null;
        $caughtBot = null;

        $bot->catch(function (\Throwable $e, Update $update, Telegram $b) use (&$caught, &$caughtError, &$caughtUpdate, &$caughtBot) {
            $caught = true;
            $caughtError = $e;
            $caughtUpdate = $update;
            $caughtBot = $b;
        });

        $bot->run(function (Update $update) {
            throw new RuntimeException("Something went wrong!");
        });

        $this->assertTrue($caught);
        $this->assertInstanceOf(RuntimeException::class, $caughtError);
        $this->assertSame("Something went wrong!", $caughtError->getMessage());
        $this->assertSame(100, $caughtUpdate->updateId);
        $this->assertSame($bot, $caughtBot);
    }

    public function testTypedCatchMatchesSpecificClass(): void
    {
        $payload = json_encode(['update_id' => 200]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload, safeExceptions: false));

        $customCaught = false;
        $anotherCaught = false;

        $bot->catch(AnotherDomainException::class, function () use (&$anotherCaught) {
            $anotherCaught = true;
        });

        $bot->catch(CustomDomainException::class, function (CustomDomainException $e, Update $u) use (&$customCaught) {
            $customCaught = true;
        });

        $bot->run(function () {
            throw new CustomDomainException("Custom error");
        });

        $this->assertTrue($customCaught);
        $this->assertFalse($anotherCaught);
    }

    public function testTypedCatchRethrowsWhenNoMatch(): void
    {
        $payload = json_encode(['update_id' => 300]);
        $bot = new Telegram('TEST_TOKEN');
        $bot->setRunningMode(new WebhookMode(rawInput: $payload, safeExceptions: false));

        $bot->catch(AnotherDomainException::class, function () {
            // Should not match
        });

        $this->expectException(CustomDomainException::class);
        $this->expectExceptionMessage("Unmatched error");

        $bot->run(function () {
            throw new CustomDomainException("Unmatched error");
        });
    }

    public function testAppCatchProxy(): void
    {
        $dir = sys_get_temp_dir() . '/tueen_app_catch_' . uniqid();
        @mkdir($dir, 0777, true);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        try {
            $payload = json_encode(['update_id' => 400]);
            $app = App::create($dir, ['token' => 'TEST_TOKEN', 'mode' => 'webhook']);
            $app->bot()->setRunningMode(new WebhookMode(rawInput: $payload, safeExceptions: false));

            $handled = false;
            $app->catch(function (\Throwable $e, Update $update, Telegram $bot) use (&$handled) {
                $handled = true;
                $this->assertSame("App error", $e->getMessage());
                $this->assertSame(400, $update->updateId);
            });

            $app->run(function () {
                throw new RuntimeException("App error");
            });

            $this->assertTrue($handled);
        } finally {
            unset($_SERVER['REQUEST_METHOD']);
            @rmdir($dir . '/storage/flow');
            @rmdir($dir . '/storage');
            @rmdir($dir);
        }
    }

    public function testPollingModeResilienceWithFaultyUpdate(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        // A single batch containing 3 updates: 501, 502 (fails), 503
        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(200, [
                'ok' => true,
                'result' => [
                    ['update_id' => 501],
                    ['update_id' => 502],
                    ['update_id' => 503],
                ],
            ]));

        $config = Telegram::create('TEST_TOKEN')->withHttpClient($mockHttp)->build();
        $bot = new Telegram($config);

        $polling = new PollingMode(timeout: 5, limit: 10);
        $this->assertFalse($polling->isStopOnError());

        $bot->setRunningMode($polling);

        $processed = [];

        // No catch handler registered on bot, so 502 will throw unhandled exception
        $bot->run(function (Update $update) use (&$processed, $polling) {
            $processed[] = $update->updateId;

            if ($update->updateId === 502) {
                throw new RuntimeException("Crash on 502!");
            }

            if ($update->updateId === 503) {
                $polling->stop();
            }
        });

        // Update 501 and 503 were processed, 502 threw but was safely caught and logged!
        $this->assertSame([501, 502, 503], $processed);
        // Offset advanced to 504
        $this->assertSame(504, $polling->getOffset());
    }

    public function testPollingModeStopOnErrorRethrows(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        $mockHttp->expects($this->once())
            ->method('send')
            ->willReturn(new Response(200, [
                'ok' => true,
                'result' => [
                    ['update_id' => 601],
                    ['update_id' => 602],
                ],
            ]));

        $config = Telegram::create('TEST_TOKEN')->withHttpClient($mockHttp)->build();
        $bot = new Telegram($config);

        $polling = new PollingMode(timeout: 5, limit: 10, stopOnError: true);
        $this->assertTrue($polling->isStopOnError());
        $bot->setRunningMode($polling);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Fatal on 601");

        $bot->run(function (Update $update) {
            throw new RuntimeException("Fatal on 601");
        });
    }

    public function testWebhookModeSafeExceptionsPreventsCrash(): void
    {
        $payload = json_encode(['update_id' => 700]);
        $bot = new Telegram('TEST_TOKEN');
        $webhook = new WebhookMode(rawInput: $payload, safeExceptions: true);
        $this->assertTrue($webhook->isSafeExceptions());
        $bot->setRunningMode($webhook);

        // When unhandled exception is thrown, safeExceptions=true catches it, calls safeResponse(), and returns Update
        $result = $bot->run(function () {
            throw new RuntimeException("Webhook uncaught crash");
        });

        $this->assertInstanceOf(Update::class, $result);
        $this->assertSame(700, $result->updateId);
    }

    public function testReplyHelperResolvesChatIdFromContext(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('send')
            ->with($this->anything(), $this->callback(function ($request) {
                $this->assertSame('sendMessage', $request->endpoint);
                $this->assertSame(98765, $request->parameters['chat_id']);
                $this->assertSame('Hello back!', $request->parameters['text']);
                return true;
            }))
            ->willReturn(new Response(200, [
                'ok' => true,
                'result' => [
                    'message_id' => 1234,
                    'date' => 1700000000,
                    'chat' => ['id' => 98765, 'type' => 'private'],
                    'text' => 'Hello back!',
                ],
            ]));

        $config = Telegram::create('TEST_TOKEN')->withHttpClient($mockHttp)->build();
        $bot = new Telegram($config);

        $update = new Update([
            'update_id' => 800,
            'message' => [
                'message_id' => 11,
                'date' => 1700000000,
                'chat' => ['id' => 98765, 'type' => 'private'],
                'text' => 'Ping',
            ],
        ]);

        $bot->setUpdate($update);

        $response = $bot->reply('Hello back!');
        $this->assertInstanceOf(Message::class, $response);
        $this->assertSame(1234, $response->messageId);
    }
}
