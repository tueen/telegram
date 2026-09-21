<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\MaybeInaccessibleMessage;
use Tueen\Telegram\Types\MessageEntity;

/**
 * Describes a service message about an option deleted from a poll.
 *
 * @link https://core.telegram.org/bots/api#polloptiondeleted
 */
class PollOptionDeleted extends Type
{
    /**
     * Optional. Message containing the poll from which the option was deleted, if known. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('poll_message', required: false)]
    public private(set) ?MaybeInaccessibleMessage $pollMessage = null;

    /**
     * Unique identifier of the deleted option
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
