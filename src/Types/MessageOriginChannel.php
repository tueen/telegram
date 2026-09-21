<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Chat;

/**
 * The message was originally sent to a channel chat.
 *
 * @link https://core.telegram.org/bots/api#messageoriginchannel
 */
class MessageOriginChannel extends MessageOrigin
{
    /**
     * Type of the message origin, always "channel"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * Channel chat to which the message was originally sent
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Unique message identifier inside the chat
     */
    #[Field('message_id', required: true)]
    public private(set) int $messageId;

    /**
     * Optional. Signature of the original post author
     */
    #[Field('author_signature', required: false)]
    public private(set) ?string $authorSignature = null;

}
