<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichMessageButton;

/**
 * A button.
 *
 * @link https://core.telegram.org/bots/api#richtextbutton
 */
class RichTextButton extends RichText
{
    /**
     * Type of the rich text, always "button"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * The button
     */
    #[Field('button', required: true)]
    public private(set) RichMessageButton $button;

}
