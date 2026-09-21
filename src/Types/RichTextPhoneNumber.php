<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A text with a phone number.
 *
 * @link https://core.telegram.org/bots/api#richtextphonenumber
 */
class RichTextPhoneNumber extends RichText
{
    /**
     * Type of the rich text, always "phone_number"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The phone number
     */
    #[Field('phone_number', required: true)]
    public private(set) string $phoneNumber;

}
