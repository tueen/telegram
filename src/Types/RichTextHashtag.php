<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * A hashtag.
 *
 * @link https://core.telegram.org/bots/api#richtexthashtag
 */
class RichTextHashtag extends RichText
{
    /**
     * Type of the rich text, always "hashtag"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * The text
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * The hashtag
     */
    #[Field('hashtag', required: true)]
    public private(set) string $hashtag;

}
