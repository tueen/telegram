<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A strikethrough text.
 *
 * @link https://core.telegram.org/bots/api#richtextstrikethrough
 */
class RichTextStrikethrough extends RichText
{
    /**
     * Type of the rich text, always "strikethrough"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

}
