<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 *
 * @link https://core.telegram.org/bots/api#giveawaycompleted
 */
class GiveawayCompleted extends Type
{
    /**
     * Number of winners in the giveaway
     */
    #[Field('winner_count', required: true)]
    private(set) int $winnerCount;

    /**
     * Optional. Number of undistributed prizes
     */
    #[Field('unclaimed_prize_count', required: false)]
    private(set) ?int $unclaimedPrizeCount = null;

    /**
     * Optional. Message with the giveaway that was completed, if it wasn't deleted
     */
    #[Field('giveaway_message', required: false)]
    private(set) ?Message $giveawayMessage = null;

    /**
     * Optional. True, if the giveaway is a Telegram Star giveaway. Otherwise, currently, the giveaway is a Telegram Premium giveaway.
     */
    #[Field('is_star_giveaway', required: false)]
    private(set) ?bool $isStarGiveaway = null;

}
