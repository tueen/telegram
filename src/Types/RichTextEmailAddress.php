<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

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
    public private(set) string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The email address
     */
    #[Field('email_address', required: true)]
    public private(set) string $emailAddress;

}
