<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Routing\Attributes\OnCallbackQuery;
use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Routing\Router;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class RoutingTest extends TestCase
{
    public function testCommandRouting(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $router = new Router();

        $handledCommand = null;
        $router->onCommand('start', function (Update $u, Telegram $b) use (&$handledCommand) {
            $handledCommand = 'start';
        });
        $router->onCommand('help', function (Update $u, Telegram $b) use (&$handledCommand) {
            $handledCommand = 'help';
        });

        // 1. /start message
        $startUpdate = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'date' => 1700000000,
                'text' => '/start',
                'chat' => ['id' => 1, 'type' => 'private'],
            ],
        ]);
        $router->dispatch($startUpdate, $bot);
        $this->assertSame('start', $handledCommand);

        // 2. /help@MyBot message
        $helpUpdate = new Update([
            'update_id' => 2,
            'message' => [
                'message_id' => 11,
                'date' => 1700000000,
                'text' => '/help@MyBot arg1',
                'chat' => ['id' => 1, 'type' => 'private'],
            ],
        ]);
        $router->dispatch($helpUpdate, $bot);
        $this->assertSame('help', $handledCommand);
    }

    public function testCallbackQueryRoutingWithPlaceholdersAndRegex(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $router = new Router();

        $capturedId = null;
        $router->onCallbackQuery('product:{id}', function (Update $u, Telegram $b, string $id) use (&$capturedId) {
            $capturedId = $id;
        });

        $capturedPage = null;
        $router->onCallbackQuery('/^page_(\d+)$/', function (Update $u, Telegram $b, $page = null) use (&$capturedPage) {
            $capturedPage = $u->callbackQuery?->data;
        });

        // 1. Placeholder match
        $update1 = new Update([
            'update_id' => 10,
            'callback_query' => [
                'id' => 'cb_1',
                'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'User'],
                'data' => 'product:4567',
                'chat_instance' => 'ci_1',
            ],
        ]);
        $router->dispatch($update1, $bot);
        $this->assertSame('4567', $capturedId);

        // 2. Regex match
        $update2 = new Update([
            'update_id' => 11,
            'callback_query' => [
                'id' => 'cb_2',
                'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'User'],
                'data' => 'page_42',
                'chat_instance' => 'ci_2',
            ],
        ]);
        $router->dispatch($update2, $bot);
        $this->assertSame('page_42', $capturedPage);
    }

    public function testMessagePatternRouting(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $router = new Router();

        $matched = false;
        $router->onMessage('/^hello/i', function (Update $u, Telegram $b) use (&$matched) {
            $matched = true;
        });

        $update = new Update([
            'update_id' => 20,
            'message' => [
                'message_id' => 20,
                'date' => 1700000000,
                'text' => 'Hello there!',
                'chat' => ['id' => 1, 'type' => 'private'],
            ],
        ]);

        $router->dispatch($update, $bot);
        $this->assertTrue($matched);
    }

    public function testAttributeBasedRouting(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $router = new Router();
        $controller = new TestSampleController();

        $router->registerController($controller);

        // 1. Command attribute
        $startUpdate = new Update([
            'update_id' => 30,
            'message' => [
                'message_id' => 30,
                'date' => 1700000000,
                'text' => '/start',
                'chat' => ['id' => 1, 'type' => 'private'],
            ],
        ]);
        $router->dispatch($startUpdate, $bot);
        $this->assertTrue($controller->startInvoked);

        // 2. Callback attribute
        $cbUpdate = new Update([
            'update_id' => 31,
            'callback_query' => [
                'id' => 'cb_3',
                'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'User'],
                'data' => 'confirm:99',
                'chat_instance' => 'ci_3',
            ],
        ]);
        $router->dispatch($cbUpdate, $bot);
        $this->assertSame('99', $controller->confirmId);
    }

    public function testFallbackHandler(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $router = new Router();

        $fallbackCalled = false;
        $router->onFallback(function (Update $u, Telegram $b) use (&$fallbackCalled) {
            $fallbackCalled = true;
        });

        $unhandledUpdate = new Update([
            'update_id' => 40,
            'poll' => [
                'id' => 'poll_1',
                'question' => 'Q?',
                'options' => [],
                'total_voter_count' => 0,
                'is_closed' => false,
                'is_anonymous' => true,
                'type' => 'regular',
                'allows_multiple_answers' => false,
            ],
        ]);

        $router->dispatch($unhandledUpdate, $bot);
        $this->assertTrue($fallbackCalled);
    }

    public function testTelegramFluentRoutingMethods(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $called = false;

        $bot->onCommand('test', function (Update $u, Telegram $b) use (&$called) {
            $called = true;
        });

        $bot->router->dispatch(new Update([
            'update_id' => 50,
            'message' => [
                'message_id' => 50,
                'date' => 1700000000,
                'text' => '/test',
                'chat' => ['id' => 1, 'type' => 'private'],
            ],
        ]), $bot);

        $this->assertTrue($called);
    }

    public function testChannelPostAndBusinessMessagePatternMatching(): void
    {
        $bot = new Telegram('TEST_TOKEN');
        $router = new Router();
        $matched = false;

        $router->on('channel_post', function (Update $u) use (&$matched) {
            $matched = true;
        });

        $channelUpdate = new Update([
            'update_id' => 60,
            'channel_post' => [
                'message_id' => 60,
                'date' => 1700000000,
                'text' => 'News broadcast',
                'chat' => ['id' => -1001234567, 'type' => 'channel', 'title' => 'My Channel'],
            ],
        ]);

        $router->dispatch($channelUpdate, $bot);
        $this->assertTrue($matched);
    }
}

class TestSampleController
{
    public bool $startInvoked = false;
    public ?string $confirmId = null;

    #[OnCommand('start')]
    public function start(Update $update, Telegram $bot): void
    {
        $this->startInvoked = true;
    }

    #[OnCallbackQuery('confirm:{id}')]
    public function confirm(Update $update, Telegram $bot, string $id): void
    {
        $this->confirmId = $id;
    }
}
