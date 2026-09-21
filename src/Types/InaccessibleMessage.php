<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Chat;

/**
 * This object describes a message that was deleted or is otherwise inaccessible to the bot.
 *
 * @link https://core.telegram.org/bots/api#inaccessiblemessage
 */
class InaccessibleMessage extends MaybeInaccessibleMessage
{
    /**
     * Chat the message belonged to
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Unique message identifier inside the chat
     */
    #[Field('message_id', required: true)]
    public private(set) int $messageId;

    /**
     * Always 0. The field can be used to differentiate regular and inaccessible messages.
     */
    #[Field('date', required: true)]
    public private(set) int $date;

}
