<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to remove a reaction from a message in a group or a supergroup chat. The bot must have the 'can_delete_messages' administrator right in the chat. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletemessagereaction
 */
#[ApiMethod('deleteMessageReaction', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class DeleteMessageReaction extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of the target message
     */
    #[Field('message_id', required: true)]
    public int $messageId;

    /**
     * Identifier of the user whose reaction will be removed, if the reaction was added by a user
     */
    #[Field('user_id', required: false)]
    public ?int $userId = null;

    /**
     * Identifier of the chat whose reaction will be removed, if the reaction was added by a chat
     */
    #[Field('actor_chat_id', required: false)]
    public ?int $actorChatId = null;

    public function __construct(
        int|string $chatId,
        int $messageId,
        ?int $userId = null,
        ?int $actorChatId = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($userId !== null) $this->userId = $userId;
        if ($actorChatId !== null) $this->actorChatId = $actorChatId;
    }

    public static function make(
        int|string $chatId,
        int $messageId,
        ?int $userId = null,
        ?int $actorChatId = null
    ): static
    {
        return new static($chatId, $messageId, $userId, $actorChatId);
    }
}
