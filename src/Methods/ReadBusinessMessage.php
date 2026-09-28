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
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Marks incoming message as read on behalf of a business account. Requires the can_read_messages business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#readbusinessmessage
 *
 * @throws ChatNotFoundException
 * @throws MessageNotFoundException
 * @throws NotEnoughRightsException
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('readBusinessMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class ReadBusinessMessage extends Method
{
    /**
     * Unique identifier of the business connection on behalf of which to read the message
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier of the chat in which the message was received. The chat must have been active in the last 24 hours.
     */
    #[Field('chat_id', required: true)]
    public ?int $chatId = null;

    /**
     * Unique identifier of the message to mark as read
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    public function __construct(
        ?string $businessConnectionId = null,
        ?int $chatId = null,
        ?int $messageId = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
