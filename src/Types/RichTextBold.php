<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A bold text.
 *
 * @link https://core.telegram.org/bots/api#richtextbold
 */
class RichTextBold extends RichText
{
    /**
     * Type of the rich text, always "bold"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

}
