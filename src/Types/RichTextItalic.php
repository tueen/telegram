<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * An italicized text.
 *
 * @link https://core.telegram.org/bots/api#richtextitalic
 */
class RichTextItalic extends RichText
{
    /**
     * Type of the rich text, always "italic"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

}
