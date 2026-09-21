<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes the rating of a user based on their Telegram Star spendings.
 *
 * @link https://core.telegram.org/bots/api#userrating
 */
class UserRating extends Type
{
    /**
     * Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
     */
    #[Field('level', required: true)]
    public private(set) int $level;

    /**
     * Numerical value of the user's rating; the higher the rating, the better
     */
    #[Field('rating', required: true)]
    public private(set) int $rating;

    /**
     * The rating value required to get the current level
     */
    #[Field('current_level_rating', required: true)]
    public private(set) int $currentLevelRating;

    /**
     * Optional. The rating value required to get to the next level; omitted if the maximum level was reached
     */
    #[Field('next_level_rating', required: false)]
    public private(set) ?int $nextLevelRating = null;

}
