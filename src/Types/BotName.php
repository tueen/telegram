<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents the bot's name.
 *
 * @link https://core.telegram.org/bots/api#botname
 */
class BotName extends Type
{
    /**
     * The bot's name
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

}
