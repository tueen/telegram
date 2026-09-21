<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents the bot's description.
 *
 * @link https://core.telegram.org/bots/api#botdescription
 */
class BotDescription extends Type
{
    /**
     * The bot's description
     */
    #[Field('description', required: true)]
    public private(set) string $description;

}
