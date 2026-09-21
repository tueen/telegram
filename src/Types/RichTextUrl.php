<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

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
    public private(set) string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * URL of the link
     */
    #[Field('url', required: true)]
    public private(set) string $url;

}
