<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents reaction changes on a message with anonymous reactions.
 *
 * @link https://core.telegram.org/bots/api#messagereactioncountupdated
 */
class MessageReactionCountUpdated extends Type
{
    /**
     * The chat containing the message
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Unique message identifier inside the chat
     */
    #[Field('message_id', required: true)]
    public private(set) int $messageId;

    /**
     * Date of the change in Unix time
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * List of reactions that are present on the message
     * @var ReactionCount[]|null
     */
    #[Field('reactions', required: true)]
    #[ArrayOf(ReactionCount::class)]
    public private(set) array $reactions;

}
