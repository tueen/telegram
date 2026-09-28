<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MessageEntityType;

/**
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 *
 * @link https://core.telegram.org/bots/api#messageentity
 */
class MessageEntity extends Type
{
    /**
     * Type of the entity. Currently, can be "mention" (@username), "hashtag" (#hashtag or #hashtag@chatusername), "cashtag" ($USD or $USD@chatusername), "bot_command" (/start@jobs_bot), "url" (https://telegram.org), "email" (do-not-reply@telegram.org), "phone_number" (+1-212-555-0123), "bold" (bold text), "italic" (italic text), "underline" (underlined text), "strikethrough" (strikethrough text), "spoiler" (spoiler message), "blockquote" (block quotation), "expandable_blockquote" (collapsed-by-default block quotation), "code" (monowidth string), "pre" (monowidth block), "text_link" (for clickable text URLs), "text_mention" (for users without usernames), "custom_emoji" (for inline custom emoji stickers), or "date_time" (for formatted date and time).
     */
    #[Field('type', required: true)]
    private(set) MessageEntityType|string $type;

    /**
     * Offset in UTF-16 code units to the start of the entity
     */
    #[Field('offset', required: true)]
    private(set) int $offset;

    /**
     * Length of the entity in UTF-16 code units
     */
    #[Field('length', required: true)]
    private(set) int $length;

    /**
     * Optional. For "text_link" only, URL that will be opened after user taps on the text
     */
    #[Field('url', required: false)]
    private(set) ?string $url = null;

    /**
     * Optional. For "text_mention" only, the mentioned user
     */
    #[Field('user', required: false)]
    private(set) ?User $user = null;

    /**
     * Optional. For "pre" only, the programming language of the entity text
     */
    #[Field('language', required: false)]
    private(set) ?string $language = null;

    /**
     * Optional. For "custom_emoji" only, unique identifier of the custom emoji. Use getCustomEmojiStickers to get full information about the sticker.
     */
    #[Field('custom_emoji_id', required: false)]
    private(set) ?string $customEmojiId = null;

    /**
     * Optional. For "date_time" only, the Unix time associated with the entity
     */
    #[Field('unix_time', required: false)]
    private(set) ?int $unixTime = null;

    /**
     * Optional. For "date_time" only, the string that defines the formatting of the date and time. See date-time entity formatting for more details.
     */
    #[Field('date_time_format', required: false)]
    private(set) ?string $dateTimeFormat = null;

}
