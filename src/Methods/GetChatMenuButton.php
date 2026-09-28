<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\MenuButton;

/**
 * Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 *
 * @link https://core.telegram.org/bots/api#getchatmenubutton
 *
 * @throws ChatNotFoundException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getChatMenuButton', 'POST')]
#[ReturnType(MenuButton::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::FloodWait])]
class GetChatMenuButton extends Method
{
    /**
     * Unique identifier for the target private chat. If not specified, the bot's default menu button will be returned.
     */
    #[Field('chat_id', required: false)]
    public ?int $chatId = null;

    public function __construct(
        ?int $chatId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
