<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Chat;

/**
 * The message was originally sent on behalf of a chat to a group chat.
 *
 * @link https://core.telegram.org/bots/api#messageoriginchat
 */
class MessageOriginChat extends MessageOrigin
{
    /**
     * Type of the message origin, always "chat"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Date the message was sent originally in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * Chat that sent the message originally
     */
    #[Field('sender_chat', required: true)]
    public private(set) Chat $senderChat;

    /**
     * Optional. For messages originally sent by an anonymous chat administrator, original message author signature
     */
    #[Field('author_signature', required: false)]
    public private(set) ?string $authorSignature = null;

}
