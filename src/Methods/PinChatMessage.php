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
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to add a message to the list of pinned messages in a chat. In private chats and channel direct messages chats, all non-service messages can be pinned. Conversely, the bot must be an administrator with the 'can_pin_messages' right or the 'can_edit_messages' right to pin messages in groups and channels respectively. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#pinchatmessage
 *
 * @throws ChatNotFoundException
 * @throws BotBlockedException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('pinChatMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::BotBlocked, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class PinChatMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Identifier of a message to pin
     */
    #[Field('message_id', required: true)]
    public ?int $messageId = null;

    /**
     * Unique identifier of the business connection on behalf of which the message will be pinned
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Pass True if it is not necessary to send a notification to all chat members about the new pinned message. Notifications are always disabled in channels and private chats.
     */
    #[Field('disable_notification', required: false)]
    public ?bool $disableNotification = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $businessConnectionId = null,
        ?bool $disableNotification = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
