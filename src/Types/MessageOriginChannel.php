<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MessageOriginType;

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
    private(set) MessageOriginType|string|null $type = null;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * Channel chat to which the message was originally sent
     */
    #[Field('chat', required: true)]
    private(set) ?Chat $chat = null;

    /**
     * Unique message identifier inside the chat
     */
    #[Field('message_id', required: true)]
    private(set) ?int $messageId = null;

    /**
     * Optional. Signature of the original post author
     */
    #[Field('author_signature', required: false)]
    private(set) ?string $authorSignature = null;

}
