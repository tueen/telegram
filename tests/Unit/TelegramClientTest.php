<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

class TelegramClientTest extends TestCase
{
    public function testBotApiVersionConstant(): void
    {
        $this->assertSame('10.3', Telegram::BOT_API_VERSION);
        $this->assertSame(Telegram::BOT_API_VERSION, Telegram::API_VERSION);
    }

    public function testFluentBuilder(): void
    {
        $config = Telegram::create('TEST_TOKEN_123')
            ->withTimeout(45.0)
            ->withProxy('http://127.0.0.1:10809')
            ->withRetryCount(5)
            ->build();

        $this->assertSame('TEST_TOKEN_123', $config->botToken);
        $this->assertSame(45.0, $config->timeout);
        $this->assertSame('http://127.0.0.1:10809', $config->proxy);
        $this->assertSame(5, $config->retryCount);
        $this->assertSame('https://api.telegram.org/botTEST_TOKEN_123', $config->getBaseApiUrl());
    }

    public function testDynamicMethodDispatchAndDeserialization(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        // Expect getMe call
        $mockHttp->expects($this->once())
            ->method('send')
            ->with(
                $this->anything(),
                $this->callback(function (Request $req) {
                    return $req->endpoint === 'getMe' && $req->httpMethod === 'POST';
                })
            )
            ->willReturn(new Response(
                statusCode: 200,
                data: [
                    'ok' => true,
                    'result' => [
                        'id' => 12345678,
                        'is_bot' => true,
                        'first_name' => 'QueenBot',
                        'username' => 'queen_bot',
                    ],
                ]
            ));

        $config = Telegram::create('MOCK_TOKEN')
            ->withHttpClient($mockHttp)
            ->build();

        $telegram = new Telegram($config);

        /** @var User $botUser */
        $botUser = $telegram->getMe();

        $this->assertInstanceOf(User::class, $botUser);
        $this->assertSame(12345678, $botUser->id);
        $this->assertTrue($botUser->isBot);
        $this->assertSame('QueenBot', $botUser->firstName);
        $this->assertSame('queen_bot', $botUser->username);
    }

    public function testDynamicSendMessageWithParameters(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        $mockHttp->expects($this->once())
            ->method('send')
            ->with(
                $this->anything(),
                $this->callback(function (Request $req) {
                    return $req->endpoint === 'sendMessage'
                        && $req->parameters['chat_id'] === 999888
                        && $req->parameters['text'] === 'Hello from Tueen!';
                })
            )
            ->willReturn(new Response(
                statusCode: 200,
                data: [
                    'ok' => true,
                    'result' => [
                        'message_id' => 101,
                        'date' => 1700000000,
                        'text' => 'Hello from Tueen!',
                        'chat' => ['id' => 999888, 'type' => 'private'],
                    ],
                ]
            ));

        $telegram = new Telegram(Telegram::create('TOKEN')->withHttpClient($mockHttp)->build());

        /** @var Message $message */
        $message = $telegram->sendMessage(chatId: 999888, text: 'Hello from Tueen!');

        $this->assertInstanceOf(Message::class, $message);
        $this->assertSame(101, $message->messageId);
        $this->assertSame('Hello from Tueen!', $message->text);
        $this->assertSame(999888, $message->chat->id);
    }

    public function testPipelineMiddleware(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('send')
            ->willReturn(new Response(statusCode: 200, data: ['ok' => true, 'result' => true]));

        $telegram = new Telegram(Telegram::create('TOKEN')->withHttpClient($mockHttp)->build());

        $middlewareCalled = false;
        $telegram->pipe(new class($middlewareCalled) implements MiddlewareInterface {
            public function __construct(private bool &$called) {}
            public function handle(Request $request, Config $config, callable $next): Response
            {
                $this->called = true;
                return $next($request, $config);
            }
        });

        $telegram->deleteWebhook();
        $this->assertTrue($middlewareCalled);
    }

    public function testApiExceptionThrownOnFailure(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('send')
            ->willReturn(new Response(
                statusCode: 400,
                data: [
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request: chat not found',
                ]
            ));

        $telegram = new Telegram(Telegram::create('TOKEN')->withHttpClient($mockHttp)->build());

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Bad Request: chat not found');

        $telegram->sendMessage(chatId: -1, text: 'test');
    }

    public function testRateLimitExceptionThrown(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('send')
            ->willReturn(new Response(
                statusCode: 429,
                data: [
                    'ok' => false,
                    'error_code' => 429,
                    'description' => 'Too Many Requests: retry after 42',
                    'parameters' => [
                        'retry_after' => 42,
                    ],
                ]
            ));

        // Disable retry count so exception is thrown immediately
        $config = Telegram::create('TOKEN')
            ->withHttpClient($mockHttp)
            ->withRetryCount(1)
            ->build();

        $telegram = new Telegram($config);

        try {
            $telegram->sendMessage(chatId: 123, text: 'test');
            $this->fail("Expected RateLimitException was not thrown");
        } catch (RateLimitException $e) {
            $this->assertSame(429, $e->errorCode);
            $this->assertSame(42, $e->retryAfter);
        }
    }

    public function testHandleWebhook(): void
    {
        $payload = json_encode([
            'update_id' => 777111,
            'message' => [
                'message_id' => 55,
                'date' => 1700000000,
                'text' => '/start',
                'chat' => ['id' => 123, 'type' => 'private'],
            ],
        ]);

        $telegram = new Telegram('TOKEN');
        $telegram->setRunningMode(new \Tueen\Telegram\Running\WebhookMode(rawInput: $payload));

        $update = $telegram->run();

        $this->assertInstanceOf(Update::class, $update);
        $this->assertSame(777111, $update->updateId);
        $this->assertInstanceOf(Message::class, $update->message);
        $this->assertSame('/start', $update->message->text);
        $this->assertSame($update, $telegram->update);
    }

    public function testSendWithEnums(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        $sentRequests = [];
        $mockHttp->method('send')->willReturnCallback(function ($cfg, Request $req) use (&$sentRequests) {
            $sentRequests[$req->endpoint] = $req->parameters;
            return new Response(200, ['ok' => true, 'result' => true]);
        });

        $telegram = new Telegram(Telegram::create('TOKEN')->withHttpClient($mockHttp)->build());

        // 1. sendChatAction with ChatAction enum
        $telegram->sendChatAction(chatId: 12345, action: \Tueen\Telegram\Enums\ChatAction::TYPING);
        $this->assertArrayHasKey('sendChatAction', $sentRequests);
        $this->assertSame('typing', $sentRequests['sendChatAction']['action']);

        // 2. sendMessage with ParseMode enum
        $telegram->sendMessage(chatId: 12345, text: '<b>Hi</b>', parseMode: \Tueen\Telegram\Enums\ParseMode::HTML);
        $this->assertArrayHasKey('sendMessage', $sentRequests);
        $this->assertSame('HTML', $sentRequests['sendMessage']['parse_mode']);

        // 3. sendDice with DiceEmoji enum
        $telegram->sendDice(chatId: 12345, emoji: \Tueen\Telegram\Enums\DiceEmoji::SLOT);
        $this->assertArrayHasKey('sendDice', $sentRequests);
        $this->assertSame('🎰', $sentRequests['sendDice']['emoji']);
    }

    public function testSendWithExtraForwardCompatibleNamedParameters(): void
    {
        $mockHttp = $this->createMock(HttpClientInterface::class);

        $sentParams = [];
        $mockHttp->method('send')->willReturnCallback(function ($cfg, Request $req) use (&$sentParams) {
            $sentParams = $req->parameters;
            return new Response(200, ['ok' => true, 'result' => true]);
        });

        $telegram = new Telegram(Telegram::create('TOKEN')->withHttpClient($mockHttp)->build());

        // Calling sendMessage with an unannounced new future Telegram parameter:
        $telegram->sendMessage(
            chatId: 123456,
            text: 'Testing forward compatibility',
            unannouncedFutureFeature: 'super_feature',
            allow_paid_broadcast_future: true
        );

        $this->assertSame(123456, $sentParams['chat_id']);
        $this->assertSame('Testing forward compatibility', $sentParams['text']);
        $this->assertSame('super_feature', $sentParams['unannounced_future_feature']);
        $this->assertTrue($sentParams['allow_paid_broadcast_future']);
    }
}
