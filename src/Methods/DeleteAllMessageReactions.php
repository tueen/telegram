<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to remove up to 10000 recent reactions in a group or a supergroup chat added by a given user or chat. The bot must have the 'can_delete_messages' administrator right in the chat. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deleteallmessagereactions
 */
#[ApiMethod('deleteAllMessageReactions', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteAllMessageReactions extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of the user whose reactions will be removed, if the reactions were added by a user
     */
    #[Field('user_id', required: false)]
    public ?int $userId = null;

    /**
     * Identifier of the chat whose reactions will be removed, if the reactions were added by a chat
     */
    #[Field('actor_chat_id', required: false)]
    public ?int $actorChatId = null;

    public function __construct(
        int|string $chatId,
        ?int $userId = null,
        ?int $actorChatId = null,
        mixed ...$extra
    )
    {
        $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($actorChatId !== null) $this->actorChatId = $actorChatId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
