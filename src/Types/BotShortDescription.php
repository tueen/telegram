<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents the bot's short description.
 *
 * @link https://core.telegram.org/bots/api#botshortdescription
 */
class BotShortDescription extends Type
{
    /**
     * The bot's short description
     */
    #[Field('short_description', required: true)]
    public private(set) string $shortDescription;

}
