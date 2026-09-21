<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

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
    public private(set) string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The bank card number
     */
    #[Field('bank_card_number', required: true)]
    public private(set) string $bankCardNumber;

}
