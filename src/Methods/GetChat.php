<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\ChatFullInfo;

/**
 * Use this method to get up-to-date information about the chat. Returns a ChatFullInfo object on success.
 *
 * @link https://core.telegram.org/bots/api#getchat
 *
 * @throws ChatNotFoundException
 * @throws BotKickedException
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getChat', 'POST')]
#[ReturnType(ChatFullInfo::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::BotKicked, TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class GetChat extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    public function __construct(
        int|string|null $chatId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
