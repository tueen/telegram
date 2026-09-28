<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents a reaction added to a message along with the number of times it was added.
 *
 * @link https://core.telegram.org/bots/api#reactioncount
 */
class ReactionCount extends Type
{
    /**
     * Type of the reaction
     */
    #[Field('type', required: true)]
    private(set) ReactionType $type;

    /**
     * Number of times the reaction was added
     */
    #[Field('total_count', required: true)]
    private(set) int $totalCount;

}
