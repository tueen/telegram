<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to delete a forum topic along with all its messages in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_delete_messages administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deleteforumtopic
 */
#[ApiMethod('deleteForumTopic', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class DeleteForumTopic extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier for the target message thread of the forum topic
     */
    #[Field('message_thread_id', required: true)]
    public int $messageThreadId;

    public function __construct(
        int|string $chatId,
        int $messageThreadId
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
    }

    public static function make(
        int|string $chatId,
        int $messageThreadId
    ): static
    {
        return new static($chatId, $messageThreadId);
    }
}
