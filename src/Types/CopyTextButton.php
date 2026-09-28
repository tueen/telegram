<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an inline keyboard button that copies specified text to the clipboard.
 *
 * @link https://core.telegram.org/bots/api#copytextbutton
 */
class CopyTextButton extends Type
{
    /**
     * The text to be copied to the clipboard; 1-256 characters
     */
    #[Field('text', required: true)]
    private(set) string $text;

}
