<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes an amount of Telegram Stars.
 *
 * @link https://core.telegram.org/bots/api#staramount
 */
class StarAmount extends Type
{
    /**
     * Integer amount of Telegram Stars, rounded to 0; can be negative
     */
    #[Field('amount', required: true)]
    private(set) ?int $amount = null;

    /**
     * Optional. The number of 1/1000000000 shares of Telegram Stars; from -999999999 to 999999999; can be negative if and only if amount is non-positive
     */
    #[Field('nanostar_amount', required: false)]
    private(set) ?int $nanostarAmount = null;

}
