<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Methods\SendMessage;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Testing\TelegramFake;
use Tueen\Telegram\Types\Update;

class TelegramFakeTest extends TestCase
{
    public function testFakeFactoryAndAssertions(): void
    {
        $fake = Telegram::fake([
            'getMe' => [
                'id' => 123456,
                'is_bot' => true,
                'first_name' => 'FakeBot',
                'username' => 'fake_bot',
            ],
        ]);

        $this->assertInstanceOf(TelegramFake::class, $fake);

        // 1. Assert nothing sent initially
        $fake->assertNothingSent();

        // 2. Call getMe
        $me = $fake->getMe();
        $this->assertTrue($me->ok());
        $this->assertSame('fake_bot', $me->username);

        // 3. Assert getMe was sent
        $fake->assertSent('getMe');
        $fake->assertSentCount(1);
        $fake->assertSentCount(1, 'getMe');

        // 4. Send message
        $fake->sendMessage(chatId: 1001, text: 'Hello Fake World');

        $fake->assertSent('sendMessage', function (Request $req) {
            return $req->parameters['chat_id'] === 1001 && $req->parameters['text'] === 'Hello Fake World';
        });

        // Assert by class name
        $fake->assertSent(SendMessage::class);

        // Assert not sent
        $fake->assertNotSent('sendPhoto');

        $fake->assertSentCount(2);
    }

    public function testFakeUpdateDispatching(): void
    {
        $fake = Telegram::fake();

        $handledUpdateId = null;
        $fake->onCommand('greet', function (Update $update, Telegram $bot) use (&$handledUpdateId) {
            $handledUpdateId = $update->updateId;
            $bot->sendMessage(chatId: 555, text: 'Greetings!');
        });

        $fake->fakeUpdate([
            'update_id' => 888,
            'message' => [
                'message_id' => 1,
                'date' => 1700000000,
                'text' => '/greet',
                'chat' => ['id' => 555, 'type' => 'private'],
            ],
        ]);

        $this->assertSame(888, $handledUpdateId);
        $fake->assertSent('sendMessage', fn(Request $r) => $r->parameters['text'] === 'Greetings!');
    }
}
