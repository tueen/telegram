<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the types of gifts that can be gifted to a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#acceptedgifttypes
 */
class AcceptedGiftTypes extends Type
{
    /**
     * True, if unlimited regular gifts are accepted
     */
    #[Field('unlimited_gifts', required: true)]
    private(set) ?bool $unlimitedGifts = null;

    /**
     * True, if limited regular gifts are accepted
     */
    #[Field('limited_gifts', required: true)]
    private(set) ?bool $limitedGifts = null;

    /**
     * True, if unique gifts or gifts that can be upgraded to unique for free are accepted
     */
    #[Field('unique_gifts', required: true)]
    private(set) ?bool $uniqueGifts = null;

    /**
     * True, if a Telegram Premium subscription is accepted
     */
    #[Field('premium_subscription', required: true)]
    private(set) ?bool $premiumSubscription = null;

    /**
     * True, if transfers of unique gifts from channels are accepted
     */
    #[Field('gifts_from_channels', required: true)]
    private(set) ?bool $giftsFromChannels = null;

}
