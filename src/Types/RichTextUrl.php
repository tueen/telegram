<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A text with a link.
 *
 * @link https://core.telegram.org/bots/api#richtexturl
 */
class RichTextUrl extends RichText
{
    /**
     * Type of the rich text, always "url"
     */
    #[Field('type', required: true)]
    private(set) RichTextType|string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

    /**
     * URL of the link
     */
    #[Field('url', required: true)]
    private(set) string $url;

}
