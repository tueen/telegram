<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\BotCommandScopeType;

/**
 * Represents the default scope of bot commands. Default commands are used if no commands with a narrower scope are specified for the user.
 *
 * @link https://core.telegram.org/bots/api#botcommandscopedefault
 */
class BotCommandScopeDefault extends BotCommandScope
{
    /**
     * Scope type, must be default
     */
    #[Field('type', required: true)]
    public private(set) BotCommandScopeType|string $type;

}
