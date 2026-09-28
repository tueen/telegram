<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A text with an email address.
 *
 * @link https://core.telegram.org/bots/api#richtextemailaddress
 */
class RichTextEmailAddress extends RichText
{
    /**
     * Type of the rich text, always "email_address"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The email address
     */
    #[Field('email_address', required: true)]
    private(set) ?string $emailAddress = null;

}
