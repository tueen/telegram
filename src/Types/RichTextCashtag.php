<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

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
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The cashtag
     */
    #[Field('cashtag', required: true)]
    private(set) ?string $cashtag = null;

}
