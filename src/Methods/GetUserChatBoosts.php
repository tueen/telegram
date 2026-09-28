<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\UserNotFoundException;
use Tueen\Telegram\Types\UserChatBoosts;

/**
 * Use this method to get the list of boosts added to a chat by a user. Requires administrator rights in the chat. Returns a UserChatBoosts object.
 *
 * @link https://core.telegram.org/bots/api#getuserchatboosts
 *
 * @throws ChatNotFoundException
 * @throws UserNotFoundException
 * @throws BotKickedException
 * @throws ApiException
 */
#[ApiMethod('getUserChatBoosts', 'POST')]
#[ReturnType(UserChatBoosts::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::UserNotFound, TelegramErrorCode::BotKicked])]
class GetUserChatBoosts extends Method
{
    /**
     * Unique identifier for the chat or username of the channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $userId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
