<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about the quoted part of a message that is replied to by the given message.
 *
 * @link https://core.telegram.org/bots/api#textquote
 */
class TextQuote extends Type
{
    /**
     * Text of the quoted part of a message that is replied to by the given message
     */
    #[Field('text', required: true)]
    private(set) ?string $text = null;

    /**
     * Optional. Special entities that appear in the quote. Currently, only bold, italic, underline, strikethrough, spoiler, custom_emoji, and date_time entities are kept in quotes.
     * @var MessageEntity[]|null
     */
    #[Field('entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $entities = null;

    /**
     * Approximate quote position in the original message in UTF-16 code units as specified by the sender
     */
    #[Field('position', required: true)]
    private(set) ?int $position = null;

    /**
     * Optional. True, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
     */
    #[Field('is_manual', required: false)]
    private(set) ?bool $isManual = null;

}
