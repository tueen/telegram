<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A text covered by a spoiler.
 *
 * @link https://core.telegram.org/bots/api#richtextspoiler
 */
class RichTextSpoiler extends RichText
{
    /**
     * Type of the rich text, always "spoiler"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

}
