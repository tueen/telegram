<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\DiceEmoji;

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
    private(set) DiceEmoji|string|null $emoji = null;

    /**
     * Value of the dice, 1-6 for "🎲", "🎯" and "🎳" base emoji, 1-5 for "🏀" and "⚽" base emoji, 1-64 for "🎰" base emoji
     */
    #[Field('value', required: true)]
    private(set) ?int $value = null;

}
