<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Enums\MessageType;
use Tueen\Telegram\Types\Message;

class MessageTypeTest extends TestCase
{
    public function testTextMessageAndCommandHelpers(): void
    {
        $message = new Message([
            'message_id' => 1,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'text' => '/start 12345 hello_world',
        ]);

        $this->assertSame(MessageType::TEXT, $message->type);
        $this->assertSame(MessageType::TEXT, $message->getType());
        $this->assertTrue($message->isType(MessageType::TEXT));
        $this->assertFalse($message->isType(MessageType::PHOTO));

        $this->assertTrue($message->isCommand());
        $this->assertSame('start', $message->getCommand());
        $this->assertSame(['12345', 'hello_world'], $message->getArgs());
        $this->assertSame('/start 12345 hello_world', $message->getText());
    }

    public function testCommandWithBotUsername(): void
    {
        $message = new Message([
            'message_id' => 2,
            'date' => 1700000000,
            'chat' => ['id' => -100123, 'type' => 'supergroup'],
            'text' => '/ban@my_queen_bot 999 spamming in chat',
        ]);

        $this->assertTrue($message->isCommand());
        $this->assertSame('ban', $message->getCommand());
        $this->assertSame(['999', 'spamming', 'in', 'chat'], $message->getArgs());
    }

    public function testNonCommandTextMessage(): void
    {
        $message = new Message([
            'message_id' => 3,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'text' => 'Just a plain text message',
        ]);

        $this->assertSame(MessageType::TEXT, $message->type);
        $this->assertFalse($message->isCommand());
        $this->assertNull($message->getCommand());
        $this->assertSame([], $message->getArgs());
    }

    public function testPhotoMessage(): void
    {
        $message = new Message([
            'message_id' => 4,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'photo' => [
                [
                    'file_id' => 'ph_thumb',
                    'file_unique_id' => 'u1',
                    'width' => 100,
                    'height' => 100,
                ],
                [
                    'file_id' => 'ph_large',
                    'file_unique_id' => 'u2',
                    'width' => 800,
                    'height' => 800,
                ],
            ],
            'caption' => 'Check this photo',
        ]);

        $this->assertSame(MessageType::PHOTO, $message->type);
        $this->assertTrue($message->isType(MessageType::PHOTO));
        $this->assertSame('Check this photo', $message->getText());
        $this->assertFalse($message->isCommand());
    }

    public function testVoiceMessage(): void
    {
        $message = new Message([
            'message_id' => 5,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'voice' => [
                'file_id' => 'voice_123',
                'file_unique_id' => 'u3',
                'duration' => 12,
            ],
        ]);

        $this->assertSame(MessageType::VOICE, $message->type);
        $this->assertTrue($message->isType(MessageType::VOICE));
    }

    public function testRichMessage(): void
    {
        $message = new Message([
            'message_id' => 6,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'rich_message' => [
                'type' => 'rich_text',
            ],
        ]);

        $this->assertSame(MessageType::RICH_MESSAGE, $message->type);
        $this->assertTrue($message->isType(MessageType::RICH_MESSAGE));
    }

    public function testLivePhotoMessage(): void
    {
        $message = new Message([
            'message_id' => 7,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'live_photo' => [
                'file_id' => 'lp_123',
                'file_unique_id' => 'u4',
                'width' => 800,
                'height' => 800,
            ],
        ]);

        $this->assertSame(MessageType::LIVE_PHOTO, $message->type);
        $this->assertTrue($message->isType(MessageType::LIVE_PHOTO));
    }

    public function testGiftMessage(): void
    {
        $message = new Message([
            'message_id' => 8,
            'date' => 1700000000,
            'chat' => ['id' => 123, 'type' => 'private'],
            'gift' => [
                'id' => 'gift_123',
                'sticker' => [
                    'file_id' => 'stk_1',
                    'file_unique_id' => 'u5',
                    'type' => 'regular',
                    'width' => 512,
                    'height' => 512,
                    'is_animated' => false,
                    'is_video' => false,
                ],
                'star_count' => 10,
            ],
        ]);

        $this->assertSame(MessageType::GIFT, $message->type);
        $this->assertTrue($message->isType(MessageType::GIFT));
    }

    public function testFindAnyTextWithTextCaptionAndRichMessage(): void
    {
        // 1. Text message
        $textMsg = new Message(['message_id' => 10, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => 'Hello Text']);
        $this->assertSame('Hello Text', $textMsg->findAnyText());
        $this->assertSame('Hello Text', $textMsg->findText());

        // 2. Caption message
        $captionMsg = new Message(['message_id' => 11, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'caption' => 'Photo Caption']);
        $this->assertSame('Photo Caption', $captionMsg->findAnyText());
        $this->assertSame('Photo Caption', $captionMsg->findText());

        // 3. Rich formatted message
        $richMsg = new Message([
            'message_id' => 12,
            'date' => 1700000000,
            'chat' => ['id' => 1, 'type' => 'private'],
            'rich_message' => [
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'text' => [
                            'type' => 'bold',
                            'text' => 'Welcome to Tueen',
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'text' => 'Enjoy modern PHP.',
                    ],
                ],
            ],
        ]);
        $this->assertSame("Welcome to Tueen\nEnjoy modern PHP.", $richMsg->findAnyText());
        $this->assertSame("Welcome to Tueen\nEnjoy modern PHP.", $richMsg->findText());

        // 4. Message without any text
        $emptyMsg = new Message(['message_id' => 13, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private']]);
        $this->assertNull($emptyMsg->findAnyText());
        $this->assertNull($emptyMsg->findText());
    }

    public function testVariadicIsTypeAndIsMessage(): void
    {
        $message = new Message(['message_id' => 14, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => 'Testing']);

        $this->assertTrue($message->isType(MessageType::PHOTO, MessageType::TEXT));
        $this->assertTrue($message->isType('photo', 'text'));
        $this->assertFalse($message->isType(MessageType::AUDIO, MessageType::VIDEO));

        $this->assertTrue($message->isMessage());
        $this->assertTrue($message->isMessage(MessageType::TEXT));
        $this->assertTrue($message->isMessage('text'));
        $this->assertFalse($message->isMessage(MessageType::VOICE));
    }

    public function testIsRepliedToMessage(): void
    {
        $original = new Message(['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => 'Original']);

        $reply = new Message([
            'message_id' => 101,
            'date' => 1700000000,
            'chat' => ['id' => 1, 'type' => 'private'],
            'text' => 'Reply message',
            'reply_to_message' => [
                'message_id' => 100,
                'date' => 1700000000,
                'chat' => ['id' => 1, 'type' => 'private'],
                'text' => 'Original',
            ],
        ]);

        $this->assertTrue($reply->isRepliedToMessage());
        $this->assertTrue($reply->isRepliedToMessage(100));
        $this->assertTrue($reply->isRepliedToMessage($original));
        $this->assertFalse($reply->isRepliedToMessage(999));

        $nonReply = new Message(['message_id' => 102, 'date' => 1700000000, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => 'No reply']);
        $this->assertFalse($nonReply->isRepliedToMessage());
        $this->assertFalse($nonReply->isRepliedToMessage(100));
    }

    public function testChatAndUserFullNameProperty(): void
    {
        // 1. Group / Channel Chat with Title
        $groupChat = new \Tueen\Telegram\Types\Chat([
            'id' => -100123,
            'type' => 'supergroup',
            'title' => 'Vue & PHP Enthusiasts',
        ]);
        $this->assertSame('Vue & PHP Enthusiasts', $groupChat->fullName);
        $this->assertSame('Vue & PHP Enthusiasts', $groupChat->getFullName());

        // 2. Private Chat with First and Last Name
        $privateChat = new \Tueen\Telegram\Types\Chat([
            'id' => 555,
            'type' => 'private',
            'first_name' => 'Elsiom',
            'last_name' => 'Dev',
        ]);
        $this->assertSame('Elsiom Dev', $privateChat->fullName);
        $this->assertSame('Elsiom Dev', $privateChat->getFullName());

        // 3. Private Chat with only First Name
        $singleNameChat = new \Tueen\Telegram\Types\Chat([
            'id' => 556,
            'type' => 'private',
            'first_name' => 'Queen',
        ]);
        $this->assertSame('Queen', $singleNameChat->fullName);

        // 4. User Full Name
        $user = new \Tueen\Telegram\Types\User([
            'id' => 777,
            'is_bot' => false,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);
        $this->assertSame('Jane Doe', $user->fullName);
        $this->assertSame('Jane Doe', $user->getFullName());
    }
}
