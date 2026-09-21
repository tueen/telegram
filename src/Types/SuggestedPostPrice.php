<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes the price of a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostprice
 */
class SuggestedPostPrice extends Type
{
    /**
     * Currency in which the post will be paid. Currently, must be one of "XTR" for Telegram Stars or "TON" for TON grams.
     */
    #[Field('currency', required: true)]
    public private(set) string $currency;

    /**
     * The amount of the currency that will be paid for the post in the smallest units of the currency, i.e. Telegram Stars or nanograms. Currently, price in Telegram Stars must be between 5 and 100000, and price in nanograms must be between 10000000 and 10000000000000.
     */
    #[Field('amount', required: true)]
    public private(set) int $amount;

}
