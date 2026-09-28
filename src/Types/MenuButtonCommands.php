<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MenuButtonType;

/**
 * Represents a menu button, which opens the bot's list of commands.
 *
 * @link https://core.telegram.org/bots/api#menubuttoncommands
 */
class MenuButtonCommands extends MenuButton
{
    /**
     * Type of the button, must be commands
     */
    #[Field('type', required: true)]
    private(set) MenuButtonType|string $type;

}
