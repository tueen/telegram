<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A mention by a username.
 *
 * @link https://core.telegram.org/bots/api#richtextmention
 */
class RichTextMention extends RichText
{
    /**
     * Type of the rich text, always "mention"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

    /**
     * The username
     */
    #[Field('username', required: true)]
    private(set) string $username;

}
