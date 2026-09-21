<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes that no specific value for the menu button was set.
 *
 * @link https://core.telegram.org/bots/api#menubuttondefault
 */
class MenuButtonDefault extends MenuButton
{
    /**
     * Type of the button, must be default
     */
    #[Field('type', required: true)]
    public private(set) string $type;

}
