<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A text with a bank card number.
 *
 * @link https://core.telegram.org/bots/api#richtextbankcardnumber
 */
class RichTextBankCardNumber extends RichText
{
    /**
     * Type of the rich text, always "bank_card_number"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

    /**
     * The bank card number
     */
    #[Field('bank_card_number', required: true)]
    private(set) string $bankCardNumber;

}
