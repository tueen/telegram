<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Enums\ChatAction;
use Tueen\Telegram\Methods\SendMessage;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

final class ContextResolverTest extends TestCase
{
    private function createMessageUpdate(
        int $chatId = 123456,
        int $userId = 789012,
        int $messageId = 42,
        ?int $threadId = null,
        ?string $businessConnectionId = null
    ): Update {
        $msgData = [
            'message_id' => $messageId,
            'date' => 1700000000,
            'chat' => [
                'id' => $chatId,
                'type' => 'supergroup',
                'title' => 'Test Group',
            ],
            'from' => [
                'id' => $userId,
                'is_bot' => false,
                'first_name' => 'Royal',
                'last_name' => 'Developer',
                'username' => 'royal_dev',
            ],
            'text' => 'Hello Queen',
        ];

        if ($threadId !== null) {
            $msgData['message_thread_id'] = $threadId;
        }

        if ($businessConnectionId !== null) {
            $msgData['business_connection_id'] = $businessConnectionId;
        }

        $updateData = [
            'update_id' => 99999,
        ];

        if ($businessConnectionId !== null) {
            $updateData['business_message'] = $msgData;
        } else {
            $updateData['message'] = $msgData;
        }

        return new Update($updateData);
    }

    public function testSendMessageAutoInjectsChatIdFromActiveUpdate(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Hi'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // Call without chatId
        $bot->sendMessage(text: 'Auto-injected message');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['text'] === 'Auto-injected message';
        });
    }

    public function testSendMessageAutoInjectsWhenChatIdExplicitlyNull(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Hi'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // chatId explicitly passed as null
        $bot->sendMessage(chatId: null, text: 'Null chatId injected');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['text'] === 'Null chatId injected';
        });
    }

    public function testSendMessagePreservesExplicitChatId(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 888888, 'type' => 'private'], 'text' => 'Hi'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // Different explicit chatId provided
        $bot->sendMessage(chatId: 888888, text: 'Different chat target');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 888888
                && $req->parameters['text'] === 'Different chat target';
        });
    }

    public function testPositionalSingleArgumentMapsToTextAndInjectsChatId(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Hi'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // Call with single string positional shortcut
        $bot->sendMessage('Hello from single argument!');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['text'] === 'Hello from single argument!';
        });
    }

    public function testPositionalTwoArgumentsWorksNormally(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 55555, 'type' => 'private'], 'text' => 'Two args'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // Call with explicit positional (chat_id, text)
        $bot->sendMessage(55555, 'Two args');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 55555
                && $req->parameters['text'] === 'Two args';
        });
    }

    public function testDirectMethodObjectResolvesChatIdOnSend(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Direct'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        $method = new SendMessage(text: 'Direct method call');
        $bot->send($method);

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['text'] === 'Direct method call';
        });
    }

    public function testBusinessConnectionIdAutoInjected(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Business'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(
            chatId: 123456,
            businessConnectionId: 'biz_conn_xyz999'
        ));

        $bot->sendMessage(text: 'Message via business');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && ($req->parameters['business_connection_id'] ?? null) === 'biz_conn_xyz999';
        });
    }

    public function testBusinessConnectionUpdateAutoInjectsBusinessConnectionId(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 777777, 'type' => 'private'], 'text' => 'Biz'],
        ]);

        $update = new Update([
            'update_id' => 101,
            'business_connection' => [
                'id' => 'biz_conn_standalone_123',
                'user' => [
                    'id' => 777777,
                    'is_bot' => false,
                    'first_name' => 'BusinessOwner',
                ],
                'user_chat_id' => 777777,
                'date' => 1700000000,
                'can_reply' => true,
                'is_enabled' => true,
            ],
        ]);

        $bot->setUpdate($update);

        $this->assertSame('biz_conn_standalone_123', $bot->businessConnectionId);
        $this->assertSame(777777, $bot->chatId);
        $this->assertSame(777777, $bot->userId);

        $bot->sendMessage(text: 'Hello Business Partner');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 777777
                && $req->parameters['business_connection_id'] === 'biz_conn_standalone_123';
        });
    }

    public function testMessageThreadIdAutoInjectedForForumTopic(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'supergroup'], 'text' => 'Topic'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456, threadId: 88));

        $this->assertSame(88, $bot->messageThreadId);

        $bot->sendMessage(text: 'Replying to topic');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['message_thread_id'] === 88;
        });
    }

    public function testEditMessageTextAutoInjectsChatIdAndMessageId(): void
    {
        $bot = Telegram::fake([
            'editMessageText' => ['message_id' => 42, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Edited text'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456, messageId: 42));

        $bot->editMessageText(text: 'Edited text');

        $bot->assertSent('editMessageText', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['message_id'] === 42
                && $req->parameters['text'] === 'Edited text';
        });
    }

    public function testEditMessageTextAutoInjectsInlineMessageIdWhenPresent(): void
    {
        $bot = Telegram::fake([
            'editMessageText' => true,
        ]);

        $update = new Update([
            'update_id' => 102,
            'callback_query' => [
                'id' => 'cb_query_inline_1',
                'from' => ['id' => 999, 'is_bot' => false, 'first_name' => 'User'],
                'inline_message_id' => 'inline_msg_abc123',
                'chat_instance' => 'instance_456',
            ],
        ]);

        $bot->setUpdate($update);

        $this->assertSame('inline_msg_abc123', $bot->inlineMessageId);
        $this->assertSame('cb_query_inline_1', $bot->callbackQueryId);

        $bot->editMessageText(text: 'Updated inline text');

        $bot->assertSent('editMessageText', function (Request $req): bool {
            return $req->parameters['inline_message_id'] === 'inline_msg_abc123'
                && !isset($req->parameters['chat_id'])
                && !isset($req->parameters['message_id'])
                && $req->parameters['text'] === 'Updated inline text';
        });
    }

    public function testAnswerCallbackQueryAutoInjectsCallbackQueryId(): void
    {
        $bot = Telegram::fake([
            'answerCallbackQuery' => true,
        ]);

        $update = new Update([
            'update_id' => 103,
            'callback_query' => [
                'id' => 'cb_123456789',
                'from' => ['id' => 999, 'is_bot' => false, 'first_name' => 'User'],
                'chat_instance' => 'inst_1',
                'data' => 'action:confirm',
            ],
        ]);

        $bot->setUpdate($update);

        $bot->answerCallbackQuery(text: 'Action completed!');

        $bot->assertSent('answerCallbackQuery', function (Request $req): bool {
            return $req->parameters['callback_query_id'] === 'cb_123456789'
                && $req->parameters['text'] === 'Action completed!';
        });
    }

    public function testAnswerInlineQueryAutoInjectsInlineQueryId(): void
    {
        $bot = Telegram::fake([
            'answerInlineQuery' => true,
        ]);

        $update = new Update([
            'update_id' => 104,
            'inline_query' => [
                'id' => 'iq_987654321',
                'from' => ['id' => 999, 'is_bot' => false, 'first_name' => 'User'],
                'query' => 'search query',
                'offset' => '',
            ],
        ]);

        $bot->setUpdate($update);

        $this->assertSame('iq_987654321', $bot->inlineQueryId);

        $bot->answerInlineQuery(results: []);

        $bot->assertSent('answerInlineQuery', function (Request $req): bool {
            return $req->parameters['inline_query_id'] === 'iq_987654321'
                && ($req->parameters['results'] === [] || $req->parameters['results'] === '[]');
        });
    }

    public function testUserIdAutoInjectedForUserMethods(): void
    {
        $bot = Telegram::fake([
            'getUserProfilePhotos' => ['total_count' => 0, 'photos' => []],
        ]);

        $bot->setUpdate($this->createMessageUpdate(userId: 789012));

        $this->assertSame(789012, $bot->userId);

        $bot->getUserProfilePhotos();

        $bot->assertSent('getUserProfilePhotos', function (Request $req): bool {
            return $req->parameters['user_id'] === 789012;
        });
    }

    public function testDeleteMessageAutoInjectsChatIdAndMessageId(): void
    {
        $bot = Telegram::fake([
            'deleteMessage' => true,
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456, messageId: 42));

        $bot->deleteMessage();

        $bot->assertSent('deleteMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['message_id'] === 42;
        });
    }

    public function testSendChatActionShortcut(): void
    {
        $bot = Telegram::fake([
            'sendChatAction' => true,
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        $bot->sendChatAction(ChatAction::TYPING);

        $bot->assertSent('sendChatAction', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['action'] === 'typing';
        });
    }

    public function testContextualHelperGettersOnTelegram(): void
    {
        $bot = Telegram::fake();
        $bot->setUpdate($this->createMessageUpdate(
            chatId: 123456,
            userId: 789012,
            messageId: 42,
            threadId: 99
        ));

        $this->assertSame(123456, $bot->chatId);
        $this->assertSame(789012, $bot->userId);
        $this->assertSame(42, $bot->messageId);
        $this->assertSame(99, $bot->messageThreadId);

        $user = $bot->user;
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(789012, $user->id);
        $this->assertSame('Royal', $user->firstName);

        $chat = $bot->chat;
        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertSame(123456, $chat->id);
        $this->assertSame('Test Group', $chat->title);

        $msg = $bot->message;
        $this->assertInstanceOf(Message::class, $msg);
        $this->assertSame(42, $msg->messageId);
    }

    public function testUpdatePropertyHooks(): void
    {
        $update = $this->createMessageUpdate(
            chatId: 123456,
            userId: 789012,
            messageId: 42,
            threadId: 99
        );

        // PHP 8.4 property hooks access on Update
        $this->assertSame(123456, $update->chatId);
        $this->assertSame(789012, $update->userId);
        $this->assertSame(42, $update->messageId);
        $this->assertSame(99, $update->messageThreadId);
    }

    public function testCustomParameterResolverBinding(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 100, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => 'Custom'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // Bind custom default for parse_mode
        $bot->bindDefault('parse_mode', fn(?Update $u, ?string $endpoint) => 'HTML');

        $bot->sendMessage(text: '<b>Bold custom text</b>');

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['parse_mode'] === 'HTML';
        });
    }

    public function testNumericStringTextMessageResolvesToText(): void
    {
        $bot = Telegram::fake([
            'sendMessage' => ['message_id' => 101, 'date' => 1700000000, 'chat' => ['id' => 123456, 'type' => 'private'], 'text' => '987654'],
        ]);

        $bot->setUpdate($this->createMessageUpdate(chatId: 123456));

        // Sending numeric code string like OTP
        $bot->sendMessage("987654");

        $bot->assertSent('sendMessage', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['text'] === '987654';
        });
    }

    public function testContextProperties(): void
    {
        $bot = Telegram::fake();
        $bot->setUpdate($this->createMessageUpdate(
            chatId: 123456,
            userId: 789012,
            messageId: 42,
            threadId: 99
        ));

        $context = new \Tueen\Telegram\Context\Context($bot->update, $bot->client, $bot);
        $this->assertSame(123456, $context->chatId);
        $this->assertSame(789012, $context->userId);
        $this->assertSame(42, $context->messageId);
        $this->assertSame(99, $context->messageThreadId);
        $this->assertInstanceOf(User::class, $context->user);
        $this->assertInstanceOf(Chat::class, $context->chat);
        $this->assertInstanceOf(Message::class, $context->message);
    }
}
