<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

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
    private(set) RichTextType|string|null $type = null;

    /**
     * The button
     */
    #[Field('button', required: true)]
    private(set) ?RichMessageButton $button = null;

}
