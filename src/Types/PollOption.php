<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about one answer option in a poll.
 *
 * @link https://core.telegram.org/bots/api#polloption
 */
class PollOption extends Type
{
    /**
     * Unique identifier of the option, persistent on option addition and deletion
     */
    #[Field('persistent_id', required: true)]
    private(set) ?string $persistentId = null;

    /**
     * Option text, 1-100 characters
     */
    #[Field('text', required: true)]
    private(set) ?string $text = null;

    /**
     * Optional. Special entities that appear in the option text. Currently, only custom emoji entities are allowed in poll option texts
     * @var MessageEntity[]|null
     */
    #[Field('text_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $textEntities = null;

    /**
     * Optional. Media added to the poll option
     */
    #[Field('media', required: false)]
    private(set) ?PollMedia $media = null;

    /**
     * Number of users who voted for this option; may be 0 if unknown
     */
    #[Field('voter_count', required: true)]
    private(set) ?int $voterCount = null;

    /**
     * Optional. User who added the option; omitted if the option wasn't added by a user after poll creation
     */
    #[Field('added_by_user', required: false)]
    private(set) ?User $addedByUser = null;

    /**
     * Optional. Chat that added the option; omitted if the option wasn't added by a chat after poll creation
     */
    #[Field('added_by_chat', required: false)]
    private(set) ?Chat $addedByChat = null;

    /**
     * Optional. Point in time (Unix timestamp) when the option was added; omitted if the option existed in the original poll
     */
    #[Field('addition_date', required: false)]
    private(set) ?int $additionDate = null;

}
