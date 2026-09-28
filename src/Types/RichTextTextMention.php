<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A mention of a Telegram user by their identifier.
 *
 * @link https://core.telegram.org/bots/api#richtexttextmention
 */
class RichTextTextMention extends RichText
{
    /**
     * Type of the rich text, always "text_mention"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The mentioned user
     */
    #[Field('user', required: true)]
    private(set) ?User $user = null;

}
