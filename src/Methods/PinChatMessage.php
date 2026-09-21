<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to add a message to the list of pinned messages in a chat. In private chats and channel direct messages chats, all non-service messages can be pinned. Conversely, the bot must be an administrator with the 'can_pin_messages' right or the 'can_edit_messages' right to pin messages in groups and channels respectively. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#pinchatmessage
 */
#[ApiMethod('pinChatMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class PinChatMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of a message to pin
     */
    #[Field('message_id', required: true)]
    public int $messageId;

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
        int|string $chatId,
        int $messageId,
        ?string $businessConnectionId = null,
        ?bool $disableNotification = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
    }
}
