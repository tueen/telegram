<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\ChatBoostSourceSource;
use Tueen\Telegram\Types\User;

/**
 * The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium giveaways and prize_star_count / 500 times for one year for Telegram Star giveaways.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiveaway
 */
class ChatBoostSourceGiveaway extends ChatBoostSource
{
    /**
     * Source of the boost, always "giveaway"
     */
    #[Field('source', required: true)]
    public private(set) ChatBoostSourceSource|string $source;

    /**
     * Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn't sent yet.
     */
    #[Field('giveaway_message_id', required: true)]
    public private(set) int $giveawayMessageId;

    /**
     * Optional. User that won the prize in the giveaway if any; for Telegram Premium giveaways only
     */
    #[Field('user', required: false)]
    public private(set) ?User $user = null;

    /**
     * Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     */
    #[Field('prize_star_count', required: false)]
    public private(set) ?int $prizeStarCount = null;

    /**
     * Optional. True, if the giveaway was completed, but there was no user to win the prize
     */
    #[Field('is_unclaimed', required: false)]
    public private(set) ?bool $isUnclaimed = null;

}
