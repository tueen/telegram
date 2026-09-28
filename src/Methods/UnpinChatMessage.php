<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to remove a message from the list of pinned messages in a chat. In private chats and channel direct messages chats, all messages can be unpinned. Conversely, the bot must be an administrator with the 'can_pin_messages' right or the 'can_edit_messages' right to unpin messages in groups and channels respectively. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#unpinchatmessage
 */
#[ApiMethod('unpinChatMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class UnpinChatMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier of the business connection on behalf of which the message will be unpinned
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Identifier of the message to unpin. Required if business_connection_id is specified. If not specified, the most recent pinned message (by sending date) will be unpinned.
     */
    #[Field('message_id', required: false)]
    public ?int $messageId = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageId = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
