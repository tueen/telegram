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
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatdescription
 *
 * @throws ChatNotFoundException
 * @throws NotEnoughRightsException
 * @throws BotKickedException
 * @throws ApiException
 */
#[ApiMethod('setChatDescription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::BotKicked])]
class SetChatDescription extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * New chat description, 0-255 characters
     */
    #[Field('description', required: false)]
    public ?string $description = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $description = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($description !== null) $this->description = $description;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
