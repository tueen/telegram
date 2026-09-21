<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichTextType;
use Tueen\Telegram\Types\RichText;

/**
 * A marked text.
 *
 * @link https://core.telegram.org/bots/api#richtextmarked
 */
class RichTextMarked extends RichText
{
    /**
     * Type of the rich text, always "marked"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

}
