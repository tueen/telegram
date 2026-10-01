<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Keyboards\ReplyKeyboard;
use Tueen\Telegram\Methods\GetUpdates;
use Tueen\Telegram\Methods\SendMessage;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

class ArchitecturalImprovementsTest extends TestCase
{
    public function testDirectKeyboardTypeCompatibility(): void
    {
        $inlineKb = InlineKeyboard::make()
            ->row()
            ->callback('Btn 1', 'callback_1')
            ->url('Btn 2', 'https://example.com');

        // Direct instantiation with InlineKeyboard without calling ->build()
        $msg = new SendMessage(
            chatId: 123456,
            text: 'Hello Keyboard',
            replyMarkup: $inlineKb
        );

        $this->assertInstanceOf(InlineKeyboardMarkup::class, $msg->replyMarkup);
        $data = $msg->buildRequestData()[0];
        $this->assertIsArray($data['reply_markup']);
        $this->assertArrayHasKey('inline_keyboard', $data['reply_markup']);
        $this->assertSame('Btn 1', $data['reply_markup']['inline_keyboard'][0][0]['text']);

        // Same for ReplyKeyboard
        $replyKb = ReplyKeyboard::make()
            ->resize()
            ->row()
            ->text('Contact Us');

        $msg2 = new SendMessage(
            chatId: 123456,
            text: 'Hello Reply',
            replyMarkup: $replyKb
        );

        $this->assertInstanceOf(ReplyKeyboardMarkup::class, $msg2->replyMarkup);
        $data2 = $msg2->buildRequestData()[0];
        $this->assertIsArray($data2['reply_markup']);
        $this->assertTrue($data2['reply_markup']['resize_keyboard']);
    }

    public function testUninitializedPropertySafeAccessWithoutCrashing(): void
    {
        $user = new User([]);
        // Direct camelCase property access on completely empty User type
        $this->assertNull($user->id);
        $this->assertNull($user->isBot);
        $this->assertNull($user->firstName);
        $this->assertNull($user->lastName);
        $this->assertNull($user->username);
    }

    public function testRequestTimeoutAndLongPollingDynamicTimeout(): void
    {
        $bot = Telegram::fake();

        // 1. Explicit request timeout
        $request = new Request('sendMessage', ['chat_id' => 123, 'text' => 'hi'], timeout: 45.0);
        $this->assertSame(45.0, $request->timeout);

        $cloned = $request->withTimeout(60.0);
        $this->assertSame(60.0, $cloned->timeout);

        // 2. getUpdates dynamic timeout
        $getUpdates = new GetUpdates(timeout: 30);
        [$params] = $getUpdates->buildRequestData();
        $this->assertSame(30, $params['timeout']);

        $bot->send($getUpdates);
        $bot->assertSent('getUpdates', function (Request $req) {
            // The client automatically assigns at least timeout + 15s to prevent dropping the connection
            return $req->timeout >= 45.0;
        });
    }

    public function testUpdateMiddlewarePipeline(): void
    {
        $bot = Telegram::fake();
        $trace = [];

        // First middleware: record order and pass
        $bot->middleware(function (Update $update, Telegram $b, callable $next) use (&$trace) {
            $trace[] = 'm1_before';
            $result = $next($update, $b);
            $trace[] = 'm1_after';
            return $result;
        });

        // Second middleware: record order and pass
        $bot->use(function (Update $update, Telegram $b, callable $next) use (&$trace) {
            $trace[] = 'm2_before';
            $result = $next($update, $b);
            $trace[] = 'm2_after';
            return $result;
        });

        $bot->handle(function (Update $update, Telegram $b) use (&$trace) {
            $trace[] = 'handler';
        });

        $bot->fakeUpdate(['update_id' => 101, 'message' => ['message_id' => 1, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => 'test']]);

        $this->assertSame([
            'm1_before',
            'm2_before',
            'handler',
            'm2_after',
            'm1_after',
        ], $trace);
    }

    public function testUpdateMiddlewareCanHaltExecution(): void
    {
        $bot = Telegram::fake();
        $handlerCalled = false;

        $bot->middleware(function (Update $update, Telegram $b, callable $next) {
            // Block update without calling $next
            return null;
        });

        $bot->handle(function (Update $update, Telegram $b) use (&$handlerCalled) {
            $handlerCalled = true;
        });

        $bot->fakeUpdate(['update_id' => 102]);
        $this->assertFalse($handlerCalled);
    }

    public function testWebhookModePsr7Integration(): void
    {
        $bot = Telegram::fake();
        $handlerCalled = false;

        $bot->handle(function (Update $update, Telegram $b) use (&$handlerCalled) {
            $handlerCalled = true;
        });

        $webhook = new WebhookMode(secretToken: 'secret_123');
        $bot->setRunningMode($webhook);

        $body = json_encode([
            'update_id' => 200,
            'message' => [
                'message_id' => 10,
                'date' => 1700000000,
                'chat' => ['id' => 99, 'type' => 'private'],
                'text' => 'PSR-7 payload',
            ],
        ]);

        $psrRequest = new ServerRequest(
            method: 'POST',
            uri: 'https://example.com/webhook',
            headers: [
                'Content-Type' => 'application/json',
                'X-Telegram-Bot-Api-Secret-Token' => 'secret_123',
            ],
            body: $body
        );

        $response = $webhook->processPsrRequest($psrRequest, $bot);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"ok":true}', (string)$response->getBody());
        $this->assertTrue($handlerCalled);
    }
}
