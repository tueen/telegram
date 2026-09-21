<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichTextType;
use Tueen\Telegram\Types\RichText;

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
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

}
