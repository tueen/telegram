<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents an animated emoji that displays a random value.
 *
 * @link https://core.telegram.org/bots/api#dice
 */
class Dice extends Type
{
    /**
     * Emoji on which the dice throw animation is based
     */
    #[Field('emoji', required: true)]
    public private(set) string $emoji;

    /**
     * Value of the dice, 1-6 for "🎲", "🎯" and "🎳" base emoji, 1-5 for "🏀" and "⚽" base emoji, 1-64 for "🎰" base emoji
     */
    #[Field('value', required: true)]
    public private(set) int $value;

}
