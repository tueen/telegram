<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an answer of a user in a non-anonymous poll.
 *
 * @link https://core.telegram.org/bots/api#pollanswer
 */
class PollAnswer extends Type
{
    /**
     * Unique poll identifier
     */
    #[Field('poll_id', required: true)]
    private(set) ?string $pollId = null;

    /**
     * Optional. The chat that changed the answer to the poll, if the voter is anonymous
     */
    #[Field('voter_chat', required: false)]
    private(set) ?Chat $voterChat = null;

    /**
     * Optional. The user that changed the answer to the poll, if the voter isn't anonymous
     */
    #[Field('user', required: false)]
    private(set) ?User $user = null;

    /**
     * 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
     * @var Integer[]|null
     */
    #[Field('option_ids', required: true)]
    private(set) ?array $optionIds = null;

    /**
     * Persistent identifiers of the chosen answer options. May be empty if the vote was retracted.
     * @var String[]|null
     */
    #[Field('option_persistent_ids', required: true)]
    private(set) ?array $optionPersistentIds = null;

}
