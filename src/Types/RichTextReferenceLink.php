<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A link to a reference.
 *
 * @link https://core.telegram.org/bots/api#richtextreferencelink
 */
class RichTextReferenceLink extends RichText
{
    /**
     * Type of the rich text, always "reference_link"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The link text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The name of the reference
     */
    #[Field('reference_name', required: true)]
    public private(set) string $referenceName;

}
