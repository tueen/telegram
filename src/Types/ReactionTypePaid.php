<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ReactionTypeType;

/**
 * The reaction is paid.
 *
 * @link https://core.telegram.org/bots/api#reactiontypepaid
 */
class ReactionTypePaid extends ReactionType
{
    /**
     * Type of the reaction, always "paid"
     */
    #[Field('type', required: true)]
    public private(set) ReactionTypeType|string $type;

}
