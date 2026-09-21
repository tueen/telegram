<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\ChatMember;

/**
 * Use this method to get information about a member of a chat. The method is only guaranteed to work for other users if the bot is an administrator in the chat. Returns a ChatMember object on success.
 *
 * @link https://core.telegram.org/bots/api#getchatmember
 */
#[ApiMethod('getChatMember', 'POST')]
#[ReturnType(ChatMember::class, isArray: false)]
class GetChatMember extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    public function __construct(
        int|string $chatId,
        int $userId,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
