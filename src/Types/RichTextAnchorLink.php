<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A link to an anchor.
 *
 * @link https://core.telegram.org/bots/api#richtextanchorlink
 */
class RichTextAnchorLink extends RichText
{
    /**
     * Type of the rich text, always "anchor_link"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * The link text
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

    /**
     * The name of the anchor. If the name is empty, then the link brings back to the top of the message.
     */
    #[Field('anchor_name', required: true)]
    private(set) string $anchorName;

}
