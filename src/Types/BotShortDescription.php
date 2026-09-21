<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
