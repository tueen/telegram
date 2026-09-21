<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichTextType;
use Tueen\Telegram\Types\RichText;
use Tueen\Telegram\Types\User;

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
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The mentioned user
     */
    #[Field('user', required: true)]
    public private(set) User $user;

}
