<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * A cashtag.
 *
 * @link https://core.telegram.org/bots/api#richtextcashtag
 */
class RichTextCashtag extends RichText
{
    /**
     * Type of the rich text, always "cashtag"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The cashtag
     */
    #[Field('cashtag', required: true)]
    public private(set) string $cashtag;

}
