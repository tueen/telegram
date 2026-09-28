<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

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
    private(set) ?Chat $chat = null;

    /**
     * Unique message identifier inside the chat
     */
    #[Field('message_id', required: true)]
    private(set) ?int $messageId = null;

    /**
     * Always 0. The field can be used to differentiate regular and inaccessible messages.
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

}
