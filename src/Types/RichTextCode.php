<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A monowidth text.
 *
 * @link https://core.telegram.org/bots/api#richtextcode
 */
class RichTextCode extends RichText
{
    /**
     * Type of the rich text, always "code"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

}
