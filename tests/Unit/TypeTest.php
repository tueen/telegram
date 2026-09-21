<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\ChatMember;
use Tueen\Telegram\Types\ChatMemberOwner;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\User;

class TypeTest extends TestCase
{
    public function testTypePropertyAccessAndMapping(): void
    {
        $data = [
            'message_id' => 999,
            'date' => 1700000000,
            'text' => 'Hello World',
            'chat' => [
                'id' => 12345,
                'type' => 'private',
                'first_name' => 'John',
            ],
            'from' => [
                'id' => 67890,
                'is_bot' => false,
                'first_name' => 'John Doe',
                'username' => 'johndoe',
            ],
        ];

        $message = new Message($data);

        // Check camelCase typed property
        $this->assertSame(999, $message->messageId);
        $this->assertSame(1700000000, $message->date);
        $this->assertSame('Hello World', $message->text);

        // Check nested type instances
        $this->assertInstanceOf(Chat::class, $message->chat);
        $this->assertSame(12345, $message->chat->id);
        $this->assertSame('private', $message->chat->type);

        $this->assertInstanceOf(User::class, $message->from);
        $this->assertSame(67890, $message->from->id);
        $this->assertFalse($message->from->isBot);
    }

    public function testArrayAccessAndSnakeCase(): void
    {
        $data = [
            'message_id' => 456,
            'text' => 'Test ArrayAccess',
        ];

        $message = new Message($data);

        // ArrayAccess with snake_case
        $this->assertTrue(isset($message['message_id']));
        $this->assertSame(456, $message['message_id']);
        $this->assertSame('Test ArrayAccess', $message['text']);

        // ArrayAccess with camelCase
        $this->assertTrue(isset($message['messageId']));
        $this->assertSame(456, $message['messageId']);

        // Magic property access with snake_case
        $this->assertSame(456, $message->message_id);
    }

    public function testDynamicFallbackForUnknownFields(): void
    {
        $data = [
            'message_id' => 123,
            'date' => 1700000000,
            'chat' => ['id' => 1, 'type' => 'channel'],
            'new_future_telegram_field' => 'quantum_state',
            'nested_future_object' => [
                'sub_key' => 'sub_val',
            ],
        ];

        $message = new Message($data);

        // Normal fields
        $this->assertSame(123, $message->messageId);

        // Unknown future field via property
        $this->assertSame('quantum_state', $message->new_future_telegram_field);
        $this->assertSame('quantum_state', $message->newFutureTelegramField);

        // Unknown future field via ArrayAccess
        $this->assertSame('quantum_state', $message['new_future_telegram_field']);

        // Nested unknown object automatically wrapped in Type
        $this->assertInstanceOf(Type::class, $message->nested_future_object);
        $this->assertSame('sub_val', $message->nested_future_object->sub_key);
        $this->assertSame('sub_val', $message->nested_future_object['sub_key']);
    }

    public function testPolymorphicTypeResolution(): void
    {
        $ownerData = [
            'status' => 'creator',
            'user' => [
                'id' => 100,
                'is_bot' => false,
                'first_name' => 'Owner',
            ],
            'is_anonymous' => false,
        ];

        $chatMember = Type::factory(ChatMember::class, $ownerData);

        $this->assertInstanceOf(ChatMemberOwner::class, $chatMember);
        $this->assertSame('creator', $chatMember->status);
        $this->assertSame(100, $chatMember->user->id);
    }

    public function testUnknownTypeFallback(): void
    {
        $data = ['some_field' => 'value123'];

        // If a future type does not exist as a class yet
        $type = Type::factory('FutureUnsupportedType', $data);

        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame('value123', $type->some_field);
        $this->assertSame('value123', $type['some_field']);
    }

    public function testJsonSerialization(): void
    {
        $data = [
            'id' => 555,
            'is_bot' => true,
            'first_name' => 'TueenBot',
        ];

        $user = new User($data);
        $json = json_encode($user);

        $this->assertIsString($json);
        $this->assertStringContainsString('"id":555', $json);
        $this->assertStringContainsString('"first_name":"TueenBot"', $json);
    }
}
