<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Marks incoming message as read on behalf of a business account. Requires the can_read_messages business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#readbusinessmessage
 */
#[ApiMethod('readBusinessMessage', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class ReadBusinessMessage extends Method
{
    /**
     * Unique identifier of the business connection on behalf of which to read the message
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the chat in which the message was received. The chat must have been active in the last 24 hours.
     */
    #[Field('chat_id', required: true)]
    public int $chatId;

    /**
     * Unique identifier of the message to mark as read
     */
    #[Field('message_id', required: true)]
    public int $messageId;

    public function __construct(
        string $businessConnectionId,
        int $chatId,
        int $messageId,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
