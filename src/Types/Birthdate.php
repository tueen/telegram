<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes the birthdate of a user.
 *
 * @link https://core.telegram.org/bots/api#birthdate
 */
class Birthdate extends Type
{
    /**
     * Day of the user's birth; 1-31
     */
    #[Field('day', required: true)]
    public private(set) int $day;

    /**
     * Month of the user's birth; 1-12
     */
    #[Field('month', required: true)]
    public private(set) int $month;

    /**
     * Optional. Year of the user's birth
     */
    #[Field('year', required: false)]
    public private(set) ?int $year = null;

}
