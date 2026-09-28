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
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\ChatMember;

/**
 * Use this method to get a list of administrators in a chat. Returns an Array of ChatMember objects.
 *
 * @link https://core.telegram.org/bots/api#getchatadministrators
 *
 * @throws ChatNotFoundException
 * @throws BotKickedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getChatAdministrators', 'POST')]
#[ReturnType(ChatMember::class, isArray: true)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::BotKicked, TelegramErrorCode::FloodWait])]
class GetChatAdministrators extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Pass True to additionally receive all bots that are administrators of the chat. By default, bots other than the current bot are omitted.
     */
    #[Field('return_bots', required: false)]
    public ?bool $returnBots = null;

    public function __construct(
        int|string|null $chatId = null,
        ?bool $returnBots = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($returnBots !== null) $this->returnBots = $returnBots;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
