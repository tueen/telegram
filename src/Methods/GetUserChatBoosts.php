<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\UserChatBoosts;

/**
 * Use this method to get the list of boosts added to a chat by a user. Requires administrator rights in the chat. Returns a UserChatBoosts object.
 *
 * @link https://core.telegram.org/bots/api#getuserchatboosts
 */
#[ApiMethod('getUserChatBoosts', 'POST')]
#[ReturnType(UserChatBoosts::class, isArray: false)]
class GetUserChatBoosts extends Method
{
    /**
     * Unique identifier for the chat or username of the channel in the format @username
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
