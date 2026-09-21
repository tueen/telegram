<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents a rich formatted text. Currently, it can be either a String for plain text, an Array of RichText, or any of the following types:
 * - RichTextBold
 * - RichTextItalic
 * - RichTextUnderline
 * - RichTextStrikethrough
 * - RichTextSpoiler
 * - RichTextDateTime
 * - RichTextTextMention
 * - RichTextSubscript
 * - RichTextSuperscript
 * - RichTextMarked
 * - RichTextCode
 * - RichTextCustomEmoji
 * - RichTextMathematicalExpression
 * - RichTextUrl
 * - RichTextEmailAddress
 * - RichTextPhoneNumber
 * - RichTextBankCardNumber
 * - RichTextMention
 * - RichTextHashtag
 * - RichTextCashtag
 * - RichTextBotCommand
 * - RichTextButton
 * - RichTextAnchor
 * - RichTextAnchorLink
 * - RichTextReference
 * - RichTextReferenceLink
 *
 * @link https://core.telegram.org/bots/api#richtext
 */
class RichText extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'bold') return RichTextBold::class;
        if (($data['type'] ?? '') === 'italic') return RichTextItalic::class;
        if (($data['type'] ?? '') === 'underline') return RichTextUnderline::class;
        if (($data['type'] ?? '') === 'strikethrough') return RichTextStrikethrough::class;
        if (($data['type'] ?? '') === 'spoiler') return RichTextSpoiler::class;
        if (($data['type'] ?? '') === 'date_time') return RichTextDateTime::class;
        if (($data['type'] ?? '') === 'text_mention') return RichTextTextMention::class;
        if (($data['type'] ?? '') === 'subscript') return RichTextSubscript::class;
        if (($data['type'] ?? '') === 'superscript') return RichTextSuperscript::class;
        if (($data['type'] ?? '') === 'marked') return RichTextMarked::class;
        if (($data['type'] ?? '') === 'code') return RichTextCode::class;
        if (($data['type'] ?? '') === 'custom_emoji') return RichTextCustomEmoji::class;
        if (($data['type'] ?? '') === 'mathematical_expression') return RichTextMathematicalExpression::class;
        if (($data['type'] ?? '') === 'url') return RichTextUrl::class;
        if (($data['type'] ?? '') === 'email_address') return RichTextEmailAddress::class;
        if (($data['type'] ?? '') === 'phone_number') return RichTextPhoneNumber::class;
        if (($data['type'] ?? '') === 'bank_card_number') return RichTextBankCardNumber::class;
        if (($data['type'] ?? '') === 'mention') return RichTextMention::class;
        if (($data['type'] ?? '') === 'hashtag') return RichTextHashtag::class;
        if (($data['type'] ?? '') === 'cashtag') return RichTextCashtag::class;
        if (($data['type'] ?? '') === 'bot_command') return RichTextBotCommand::class;
        if (($data['type'] ?? '') === 'button') return RichTextButton::class;
        if (($data['type'] ?? '') === 'anchor') return RichTextAnchor::class;
        if (($data['type'] ?? '') === 'anchor_link') return RichTextAnchorLink::class;
        if (($data['type'] ?? '') === 'reference') return RichTextReference::class;
        if (($data['type'] ?? '') === 'reference_link') return RichTextReferenceLink::class;
        return static::class;
    }
}
