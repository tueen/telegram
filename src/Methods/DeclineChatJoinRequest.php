<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#declinechatjoinrequest
 */
#[ApiMethod('declineChatJoinRequest', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class DeclineChatJoinRequest extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
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
        int $userId
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
    }

    public static function make(
        int|string $chatId,
        int $userId
    ): static
    {
        return new static($chatId, $userId);
    }
}
