<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichTextType;

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
    private(set) RichTextType|string|null $type = null;

    /**
     * The text
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * The hashtag
     */
    #[Field('hashtag', required: true)]
    private(set) ?string $hashtag = null;

}
