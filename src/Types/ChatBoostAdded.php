<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents a service message about a user boosting a chat.
 *
 * @link https://core.telegram.org/bots/api#chatboostadded
 */
class ChatBoostAdded extends Type
{
    /**
     * Number of boosts added by the user
     */
    #[Field('boost_count', required: true)]
    public private(set) int $boostCount;

}
