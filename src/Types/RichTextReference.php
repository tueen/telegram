<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

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
    public private(set) string $type;

    /**
     * Text of the reference
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The name of the reference
     */
    #[Field('name', required: true)]
    public private(set) string $name;

}
