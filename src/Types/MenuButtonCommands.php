<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
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
    public private(set) MenuButtonType|string $type;

}
