<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * An address in the TON blockchain.
 *
 * @link https://core.telegram.org/bots/api#richtexttonaddress
 */
class RichTextTonAddress extends RichText
{
    /**
     * Type of the rich text, always "ton_address"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string|null $type = null;

    /**
     * Text of the address
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The address
     */
    #[Field('address', required: true)]
    private(set) ?string $address = null;

}
