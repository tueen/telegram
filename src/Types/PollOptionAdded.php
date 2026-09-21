<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about an option added to a poll.
 *
 * @link https://core.telegram.org/bots/api#polloptionadded
 */
class PollOptionAdded extends Type
{
    /**
     * Optional. Message containing the poll to which the option was added, if known. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('poll_message', required: false)]
    public private(set) ?MaybeInaccessibleMessage $pollMessage = null;

    /**
     * Unique identifier of the added option
     */
    #[Field('option_persistent_id', required: true)]
    public private(set) string $optionPersistentId;

    /**
     * Option text
     */
    #[Field('option_text', required: true)]
    public private(set) string $optionText;

    /**
     * Optional. Special entities that appear in the option_text
     * @var MessageEntity[]|null
     */
    #[Field('option_text_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $optionTextEntities = null;

}
