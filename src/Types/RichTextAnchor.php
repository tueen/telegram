<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * An anchor.
 *
 * @link https://core.telegram.org/bots/api#richtextanchor
 */
class RichTextAnchor extends RichText
{
    /**
     * Type of the rich text, always "anchor"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * The name of the anchor
     */
    #[Field('name', required: true)]
    public private(set) string $name;

}
