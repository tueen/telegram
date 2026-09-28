<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A reference.
 *
 * @link https://core.telegram.org/bots/api#richtextreference
 */
class RichTextReference extends RichText
{
    /**
     * Type of the rich text, always "reference"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * Text of the reference
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

    /**
     * The name of the reference
     */
    #[Field('name', required: true)]
    private(set) string $name;

}
