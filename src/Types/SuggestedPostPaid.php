<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\Currency;

/**
 * Describes a service message about a successful payment for a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostpaid
 */
class SuggestedPostPaid extends Type
{
    /**
     * Optional. Message containing the suggested post. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('suggested_post_message', required: false)]
    public private(set) ?Message $suggestedPostMessage = null;

    /**
     * Currency in which the payment was made. Currently, one of "XTR" for Telegram Stars or "TON" for TON grams.
     */
    #[Field('currency', required: true)]
    public private(set) Currency|string $currency;

    /**
     * Optional. The amount of the currency that was received by the channel in nanograms; for payments in TON grams only
     */
    #[Field('amount', required: false)]
    public private(set) ?int $amount = null;

    /**
     * Optional. The amount of Telegram Stars that was received by the channel; for payments in Telegram Stars only
     */
    #[Field('star_amount', required: false)]
    public private(set) ?StarAmount $starAmount = null;

}
