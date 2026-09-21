<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a keyboard button to be used by a user of a Mini App.
 *
 * @link https://core.telegram.org/bots/api#preparedkeyboardbutton
 */
class PreparedKeyboardButton extends Type
{
    /**
     * Unique identifier of the keyboard button
     */
    #[Field('id', required: true)]
    public private(set) string $id;

}
