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
     * Text of the quoted part of a message that is replied to by the given message; may be empty if the quote was chosen from a rich message
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
     * Optional. Rich text of the quote if it was chosen from a rich message. May contain only plain text, RichTextBold, RichTextItalic, RichTextUnderline, RichTextStrikethrough, RichTextSpoiler, RichTextSubscript, RichTextSuperscript, RichTextDateTime, RichTextCustomEmoji, RichTextMathematicalExpression, and arrays of them.
     * @var RichText[]|null
     */
    #[Field('rich_texts', required: false)]
    #[ArrayOf(RichText::class)]
    private(set) ?array $richTexts = null;

    /**
     * Approximate quote position in the original message in UTF-16 code units as specified by the sender. For quotes from rich messages, it is the position in the text formed by joining plain texts of all quotable texts of the message, separated by line feeds.
     */
    #[Field('position', required: true)]
    private(set) ?int $position = null;

    /**
     * Optional. True, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
     */
    #[Field('is_manual', required: false)]
    private(set) ?bool $isManual = null;

}
