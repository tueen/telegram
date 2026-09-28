<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) int $position;

    /**
     * User
     */
    #[Field('user', required: true)]
    private(set) User $user;

    /**
     * Score
     */
    #[Field('score', required: true)]
    private(set) int $score;

}
