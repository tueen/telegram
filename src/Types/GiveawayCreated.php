<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a service message about the creation of a scheduled giveaway.
 *
 * @link https://core.telegram.org/bots/api#giveawaycreated
 */
class GiveawayCreated extends Type
{
    /**
     * Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     */
    #[Field('prize_star_count', required: false)]
    public private(set) ?int $prizeStarCount = null;

}
