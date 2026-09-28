<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MenuButtonType;

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
    private(set) MenuButtonType|string $type;

}
