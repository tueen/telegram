<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
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
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The username
     */
    #[Field('username', required: true)]
    public private(set) string $username;

}
