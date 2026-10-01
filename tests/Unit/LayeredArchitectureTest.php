<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\TelegramClient;
use Tueen\Telegram\Config;
use Tueen\Telegram\Context\Context;
use Tueen\Telegram\Dispatcher\UpdateDispatcher;
use Tueen\Telegram\Pipeline\RateLimitMiddleware;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Testing\FakeHttpClient;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;

class LayeredArchitectureTest extends TestCase
{
    public function testStandaloneTelegramClient(): void
    {
        $fakeHttp = new FakeHttpClient([
            'sendMessage' => [
                'message_id' => 999,
                'chat' => ['id' => 123, 'type' => 'private'],
                'text' => 'Hello from standalone client!',
            ],
        ]);

        $config = Config::builder('TEST_BOT_TOKEN')
            ->withHttpClient($fakeHttp)
            ->build();

        // 1. Pure client used independently of bot framework / routing / flows
        $client = new TelegramClient($config);

        $result = $client->sendMessage(chatId: 123, text: 'Hello from standalone client!');
        $this->assertInstanceOf(Message::class, $result);
        $this->assertSame(999, $result->messageId);
        $this->assertSame('Hello from standalone client!', $result->text);

        // Verify request was captured
        $recorded = $fakeHttp->recorded('sendMessage');
        $this->assertCount(1, $recorded);
        $this->assertSame(123, $recorded[0]['request']->parameters['chat_id']);
    }

    public function testContextScopedPerUpdate(): void
    {
        $fakeHttp = new FakeHttpClient([
            'sendMessage' => [
                'message_id' => 1,
                'chat' => ['id' => 555, 'type' => 'private'],
                'text' => 'Echo',
            ],
        ]);

        $bot = Telegram::fake([
            'sendMessage' => [
                'message_id' => 1,
                'chat' => ['id' => 555, 'type' => 'private'],
                'text' => 'Echo',
            ],
        ]);

        $update = new Update([
            'update_id' => 100,
            'message' => [
                'message_id' => 42,
                'from' => ['id' => 777, 'first_name' => 'Alice', 'is_bot' => false],
                'chat' => ['id' => 555, 'type' => 'private'],
                'text' => 'Hello',
            ],
        ]);

        $context = new Context($update, $bot->client, $bot);

        $this->assertSame(555, $context->chatId);
        $this->assertSame(777, $context->userId);
        $this->assertSame(42, $context->messageId);
        $this->assertSame('Alice', $context->user?->firstName);
        $this->assertSame('Hello', $context->message?->text);

        // Reply through context
        $reply = $context->reply('Echo');
        $this->assertInstanceOf(Message::class, $reply);
        $bot->assertSent('sendMessage', fn(Request $r) => $r->parameters['chat_id'] === 555 && $r->parameters['text'] === 'Echo');
    }

    public function testUpdateDispatcherSupportsBothContextAndUpdateSignatures(): void
    {
        $bot = Telegram::fake();
        $dispatcher = new UpdateDispatcher();

        $trace = [];

        // 1. Handler accepting Context
        $dispatcher->addHandler(function (Context $ctx) use (&$trace) {
            $trace[] = 'context:' . $ctx->chatId;
        });

        // 2. Legacy handler accepting ($update, $bot)
        $dispatcher->addHandler(function (Update $up, Telegram $b) use (&$trace) {
            $trace[] = 'legacy:' . $up->updateId;
        });

        $update = new Update([
            'update_id' => 999,
            'message' => [
                'message_id' => 1,
                'chat' => ['id' => 1234, 'type' => 'private'],
                'text' => 'Test',
            ],
        ]);

        $dispatcher->dispatch($update, $bot);

        $this->assertSame(['context:1234', 'legacy:999'], $trace);
    }

    public function testRateLimitMiddlewareWithCustomSleeper(): void
    {
        $sleepCount = 0;
        $totalSlept = 0.0;

        $customSleeper = function (float $seconds) use (&$sleepCount, &$totalSlept) {
            $sleepCount++;
            $totalSlept += $seconds;
        };

        $middleware = new RateLimitMiddleware(
            maxRequestsPerSecond: 100,
            minPerChatInterval: 2.0,
            sleeper: $customSleeper
        );

        $config = Config::builder('TEST_TOKEN')->build();
        $request = new Request('sendMessage', ['chat_id' => 111, 'text' => 'One']);
        $next = fn(Request $r, Config $c) => new \Tueen\Telegram\Client\Response(200, ['ok' => true]);

        // First call: no sleep
        $middleware->handle($request, $config, $next);
        $this->assertSame(0, $sleepCount);

        // Immediate second call to same chat: triggers custom non-blocking sleeper
        $middleware->handle($request, $config, $next);
        $this->assertGreaterThan(0, $sleepCount);
        $this->assertGreaterThan(0.0, $totalSlept);
    }

    public function testTelegramFacadeComposesComponents(): void
    {
        $bot = Telegram::fake();

        $this->assertInstanceOf(TelegramClient::class, $bot->client);
        $this->assertInstanceOf(UpdateDispatcher::class, $bot->dispatcher);
        $this->assertInstanceOf(\Tueen\Telegram\Context\ContextResolver::class, $bot->context);
        $this->assertInstanceOf(\Tueen\Telegram\Flow\FlowManager::class, $bot->flowManager);
        $this->assertInstanceOf(\Tueen\Telegram\Routing\Router::class, $bot->router);
    }

    public function testFlowPropertiesProtectedSet(): void
    {
        $flow = new class extends \Tueen\Telegram\Flow\Flow {
            public function testStep(): void {}
        };

        $ref = new \ReflectionClass($flow);
        $botProp = $ref->getProperty('bot');
        $this->assertTrue($botProp->isProtectedSet());

        $chatIdProp = $ref->getProperty('chatId');
        $this->assertTrue($chatIdProp->isProtectedSet());

        $stateProp = $ref->getProperty('state');
        $this->assertTrue($stateProp->isProtectedSet());
    }
}

