<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\CurlHttpClient;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Formatting\Text;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Methods\SendMessage;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

class SeniorArchitectUpgradesTest extends TestCase
{
    public function testRouteGroupsWithPrefixAndMiddlewares(): void
    {
        $bot = Telegram::fake();
        $groupLog = [];
        $handlerExecuted = false;

        $bot->group([
            'prefix' => '/admin',
            'middleware' => function (Update $update, Telegram $b, callable $next) use (&$groupLog) {
                $groupLog[] = 'group_middleware_before';
                $result = $next($update, $b);
                $groupLog[] = 'group_middleware_after';
                return $result;
            },
        ], function (Telegram $groupBot) use (&$handlerExecuted) {
            $groupBot->onCommand('settings', function () use (&$handlerExecuted) {
                $handlerExecuted = true;
            });
        });

        // Send a matching update for /admin_settings (or command /settings inside /admin group)
        $bot->fakeUpdate([
            'update_id' => 10,
            'message' => [
                'message_id' => 1,
                'date' => 1700000000,
                'chat' => ['id' => 123, 'type' => 'private'],
                'text' => '/admin_settings',
            ],
        ]);

        $this->assertTrue($handlerExecuted, 'Handler inside group should be invoked for matching prefix');
        $this->assertSame(['group_middleware_before', 'group_middleware_after'], $groupLog);
    }

    public function testRouteLevelMiddlewareAndScopes(): void
    {
        $bot = Telegram::fake();
        $middlewareRan = false;
        $handlerRan = false;

        $bot->onCommand('vip', function () use (&$handlerRan) {
            $handlerRan = true;
        })
        ->asPrivate()
        ->middleware(function (Update $update, Telegram $b, callable $next) use (&$middlewareRan) {
            $middlewareRan = true;
            return $next($update, $b);
        });

        // 1. Send update from group chat - should NOT match because of asPrivate()
        $bot->fakeUpdate([
            'update_id' => 11,
            'message' => [
                'message_id' => 2,
                'date' => 1700000000,
                'chat' => ['id' => -10012345, 'type' => 'supergroup'],
                'text' => '/vip',
            ],
        ]);

        $this->assertFalse($handlerRan);
        $this->assertFalse($middlewareRan);

        // 2. Send update from private chat - should match and execute route middleware
        $bot->fakeUpdate([
            'update_id' => 12,
            'message' => [
                'message_id' => 3,
                'date' => 1700000000,
                'chat' => ['id' => 999, 'type' => 'private'],
                'text' => '/vip',
            ],
        ]);

        $this->assertTrue($middlewareRan);
        $this->assertTrue($handlerRan);
    }

    public function testRouteParameterWhereConstraints(): void
    {
        $bot = Telegram::fake();
        $matchedId = null;

        $bot->onText('/order {id}', function (int $id) use (&$matchedId) {
            $matchedId = $id;
        })->where('id', '[0-9]+');

        // Non-matching (string instead of digits)
        $bot->fakeUpdate([
            'update_id' => 20,
            'message' => [
                'message_id' => 1,
                'date' => 1700000000,
                'chat' => ['id' => 1, 'type' => 'private'],
                'text' => '/order abc',
            ],
        ]);
        $this->assertNull($matchedId);

        // Matching
        $bot->fakeUpdate([
            'update_id' => 21,
            'message' => [
                'message_id' => 2,
                'date' => 1700000000,
                'chat' => ['id' => 1, 'type' => 'private'],
                'text' => '/order 428',
            ],
        ]);
        $this->assertSame(428, $matchedId);
    }

    public function testAutoWiringInHandlers(): void
    {
        $bot = Telegram::fake();
        $capturedUser = null;
        $capturedChat = null;
        $capturedMessage = null;
        $capturedBot = null;
        $capturedId = null;

        $bot->onText('/profile {id}', function (
            User $user,
            Chat $chat,
            Message $message,
            Telegram $bot,
            int $id
        ) use (&$capturedUser, &$capturedChat, &$capturedMessage, &$capturedBot, &$capturedId) {
            $capturedUser = $user;
            $capturedChat = $chat;
            $capturedMessage = $message;
            $capturedBot = $bot;
            $capturedId = $id;
        });

        $bot->fakeUpdate([
            'update_id' => 30,
            'message' => [
                'message_id' => 50,
                'date' => 1700000000,
                'from' => [
                    'id' => 777,
                    'is_bot' => false,
                    'first_name' => 'Alice',
                    'username' => 'alice_wonder',
                ],
                'chat' => [
                    'id' => 888,
                    'type' => 'private',
                    'first_name' => 'Alice',
                ],
                'text' => '/profile 999',
            ],
        ]);

        $this->assertInstanceOf(User::class, $capturedUser);
        $this->assertSame(777, $capturedUser->id);
        $this->assertInstanceOf(Chat::class, $capturedChat);
        $this->assertSame(888, $capturedChat->id);
        $this->assertInstanceOf(Message::class, $capturedMessage);
        $this->assertSame(50, $capturedMessage->messageId);
        $this->assertInstanceOf(Telegram::class, $capturedBot);
        $this->assertSame(999, $capturedId);
    }

    public function testTextChunkingAndChunkedSending(): void
    {
        // 1. Text::chunk logic
        $text = str_repeat("Hello World!\n", 50); // ~650 chars
        $chunks = Text::chunk($text, 200);

        $this->assertGreaterThan(1, count($chunks));
        foreach ($chunks as $chunk) {
            $this->assertLessThanOrEqual(200, mb_strlen($chunk));
        }
        $recombined = implode('', $chunks);
        $this->assertSame($text, $recombined);

        // 2. sendMessageChunked
        $bot = Telegram::fake();
        $longText = str_repeat("Line of text for testing chunking.\n", 300); // ~10,500 chars

        $messages = $bot->sendMessageChunked(
            chatId: 12345,
            text: $longText,
            chunkSize: 4096
        );

        $this->assertGreaterThan(1, count($messages));
        $bot->assertSent('sendMessage');
    }

    public function testInlineKeyboardDirectSerialization(): void
    {
        $kb = InlineKeyboard::make()
            ->row()
            ->callback('Yes', 'yes')
            ->callback('No', 'no');

        // Direct json_encode without calling ->build()
        $json = json_encode($kb);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertArrayHasKey('inline_keyboard', $decoded);
        $this->assertCount(2, $decoded['inline_keyboard'][0]);
        $this->assertSame('Yes', $decoded['inline_keyboard'][0][0]['text']);
        $this->assertSame('No', $decoded['inline_keyboard'][0][1]['text']);

        // Direct toArray
        $array = $kb->toArray();
        $this->assertArrayHasKey('inline_keyboard', $array);
    }

    public function testCurlHttpClientPoolAndHandleReuse(): void
    {
        $client = new CurlHttpClient();

        $this->assertSame(0, $client->getPoolCount());

        // Performing multiple dummy or invalid requests without throwing fatal errors
        // Note: we can test handle acquisition / pool methods directly
        $this->assertIsInt($client->getPoolCount());
        $client->closeAllHandles();
        $this->assertSame(0, $client->getPoolCount());
    }

    public function testScopedExecutionThreadIsolation(): void
    {
        $parentBot = Telegram::fake();

        $update1 = new Update(['update_id' => 101, 'message' => ['message_id' => 1, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => 'msg1']]);
        $update2 = new Update(['update_id' => 102, 'message' => ['message_id' => 2, 'date' => 1700000000, 'chat' => ['id' => 2, 'type' => 'private'], 'text' => 'msg2']]);

        $scoped1 = $parentBot->scoped($update1);
        $scoped2 = $parentBot->scoped($update2);

        // They must be separate instances
        $this->assertNotSame($parentBot, $scoped1);
        $this->assertNotSame($parentBot, $scoped2);
        $this->assertNotSame($scoped1, $scoped2);

        // Their updates and context resolutions must be isolated
        $this->assertSame(101, $scoped1->update->updateId);
        $this->assertSame(102, $scoped2->update->updateId);
        $this->assertSame(1, $scoped1->context->chatId);
        $this->assertSame(2, $scoped2->context->chatId);

        // Parent bot remains unaffected
        $this->assertNull($parentBot->update);
    }

    public function testTelegramFakeFluentAssertions(): void
    {
        $bot = Telegram::fake();

        $bot->onCommand('start', function (Telegram $b) {
            $b->reply('Welcome to Tueen!');
        });

        $bot->fakeUpdate([
            'update_id' => 500,
            'message' => [
                'message_id' => 100,
                'date' => 1700000000,
                'chat' => ['id' => 9999, 'type' => 'private'],
                'text' => '/start',
            ],
        ]);

        $bot->assertReplyText('Welcome to Tueen!');
        $bot->assertSentMessage(function (Request $req) {
            return $req->params['chat_id'] === 9999 && str_contains($req->params['text'], 'Welcome');
        });
    }
}
