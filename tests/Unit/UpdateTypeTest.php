<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

class UpdateTypeTest extends TestCase
{
    public function testMessageUpdateTypeAndSmartResolvers(): void
    {
        $update = new Update([
            'update_id' => 1001,
            'message' => [
                'message_id' => 50,
                'date' => 1700000000,
                'chat' => [
                    'id' => 12345,
                    'type' => 'private',
                    'first_name' => 'Alice',
                ],
                'from' => [
                    'id' => 999,
                    'is_bot' => false,
                    'first_name' => 'Alice',
                ],
                'text' => 'Hello bot',
            ],
        ]);

        $this->assertSame(UpdateType::MESSAGE, $update->type);
        $this->assertTrue($update->isType(UpdateType::MESSAGE));
        $this->assertFalse($update->isType(UpdateType::CALLBACK_QUERY));

        $msg = $update->findMessage();
        $this->assertInstanceOf(Message::class, $msg);
        $this->assertSame(50, $msg->messageId);

        $user = $update->findUser();
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(999, $user->id);

        $chat = $update->findChat();
        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertSame(12345, $chat->id);

        // Test ID finders
        $this->assertSame(50, $update->findMessageId());
        $this->assertSame(999, $update->findUserId());
        $this->assertSame(12345, $update->findChatId());

        // Test variadic isType
        $this->assertTrue($update->isType(UpdateType::CALLBACK_QUERY, UpdateType::MESSAGE));
        $this->assertTrue($update->isType('callback_query', 'message'));
        $this->assertFalse($update->isType(UpdateType::INLINE_QUERY, UpdateType::POLL));
        $this->assertFalse($update->isType('inline_query', 'poll'));
    }

    public function testCallbackQueryUpdateTypeAndSmartResolvers(): void
    {
        $update = new Update([
            'update_id' => 1002,
            'callback_query' => [
                'id' => 'cb_123',
                'chat_instance' => 'inst_1',
                'data' => 'btn_clicked',
                'from' => [
                    'id' => 888,
                    'is_bot' => false,
                    'first_name' => 'Bob',
                ],
                'message' => [
                    'message_id' => 60,
                    'date' => 1700000000,
                    'chat' => [
                        'id' => -10011223344,
                        'type' => 'supergroup',
                        'title' => 'Developers',
                    ],
                ],
            ],
        ]);

        $this->assertSame(UpdateType::CALLBACK_QUERY, $update->type);
        $this->assertTrue($update->isType(UpdateType::CALLBACK_QUERY));

        $user = $update->findUser();
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(888, $user->id);

        $chat = $update->findChat();
        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertSame(-10011223344, $chat->id);

        $msg = $update->findMessage();
        $this->assertInstanceOf(Message::class, $msg);
        $this->assertSame(60, $msg->messageId);
    }

    public function testInlineQueryUpdateType(): void
    {
        $update = new Update([
            'update_id' => 1003,
            'inline_query' => [
                'id' => 'iq_123',
                'query' => 'search query',
                'offset' => '',
                'from' => [
                    'id' => 777,
                    'is_bot' => false,
                    'first_name' => 'Charlie',
                ],
            ],
        ]);

        $this->assertSame(UpdateType::INLINE_QUERY, $update->type);
        $this->assertTrue($update->isType(UpdateType::INLINE_QUERY));
        $this->assertSame(777, $update->findUser()?->id);
        $this->assertNull($update->findChat());
        $this->assertNull($update->findMessage());
    }

    public function testChannelPostUpdateType(): void
    {
        $update = new Update([
            'update_id' => 1004,
            'channel_post' => [
                'message_id' => 70,
                'date' => 1700000000,
                'chat' => [
                    'id' => -100998877,
                    'type' => 'channel',
                    'title' => 'News Channel',
                ],
                'text' => 'Breaking News',
            ],
        ]);

        $this->assertSame(UpdateType::CHANNEL_POST, $update->type);
        $this->assertTrue($update->isType(UpdateType::CHANNEL_POST));
        $this->assertSame(-100998877, $update->findChat()?->id);
        $this->assertSame(70, $update->findMessage()?->messageId);
    }

    public function testFindFileIdFromUpdate(): void
    {
        $update = new Update([
            'update_id' => 1005,
            'message' => [
                'message_id' => 80,
                'date' => 1700000000,
                'chat' => ['id' => 123, 'type' => 'private'],
                'photo' => [
                    ['file_id' => 'ph_small', 'file_unique_id' => 'u1', 'width' => 100, 'height' => 100],
                    ['file_id' => 'ph_biggest', 'file_unique_id' => 'u2', 'width' => 1200, 'height' => 900],
                ],
            ],
        ]);

        $this->assertSame('ph_biggest', $update->findFileId());
        $this->assertSame('ph_biggest', $update->fileId);

        $textUpdate = new Update([
            'update_id' => 1006,
            'message' => [
                'message_id' => 81,
                'date' => 1700000000,
                'chat' => ['id' => 123, 'type' => 'private'],
                'text' => 'Just text',
            ],
        ]);

        $this->assertNull($textUpdate->findFileId());
        $this->assertNull($textUpdate->fileId);
    }
}
