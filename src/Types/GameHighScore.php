<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents one row of the high scores table for a game.
 *
 * @link https://core.telegram.org/bots/api#gamehighscore
 */
class GameHighScore extends Type
{
    /**
     * Position in high score table for the game
     */
    #[Field('position', required: true)]
    public private(set) int $position;

    /**
     * User
     */
    #[Field('user', required: true)]
    public private(set) User $user;

    /**
     * Score
     */
    #[Field('score', required: true)]
    public private(set) int $score;

}
