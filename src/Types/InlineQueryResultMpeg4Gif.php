<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Types\MessageEntity;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InputMessageContent;

/**
 * Represents a link to a video animation (H.264/MPEG-4 AVC video without sound). By default, this animated MPEG-4 file will be sent by the user with optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the animation.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultmpeg4gif
 */
class InlineQueryResultMpeg4Gif extends InlineQueryResult
{
    /**
     * Type of the result, must be mpeg4_gif
     */
    #[Field('type', required: true)]
    public private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * A valid URL for the MPEG4 file
     */
    #[Field('mpeg4_url', required: true)]
    public private(set) string $mpeg4Url;

    /**
     * Optional. Video width
     */
    #[Field('mpeg4_width', required: false)]
    public private(set) ?int $mpeg4Width = null;

    /**
     * Optional. Video height
     */
    #[Field('mpeg4_height', required: false)]
    public private(set) ?int $mpeg4Height = null;

    /**
     * Optional. Video duration in seconds
     */
    #[Field('mpeg4_duration', required: false)]
    public private(set) ?int $mpeg4Duration = null;

    /**
     * URL of the static (JPEG or GIF) or animated (MPEG4) thumbnail for the result
     */
    #[Field('thumbnail_url', required: true)]
    public private(set) string $thumbnailUrl;

    /**
     * Optional. MIME type of the thumbnail, must be one of "image/jpeg", "image/gif", or "video/mp4". Defaults to "image/jpeg".
     */
    #[Field('thumbnail_mime_type', required: false)]
    public private(set) ?string $thumbnailMimeType = null;

    /**
     * Optional. Title for the result
     */
    #[Field('title', required: false)]
    public private(set) ?string $title = null;

    /**
     * Optional. Caption of the MPEG-4 file to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public private(set) ParseMode|string|null $parseMode = null;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var MessageEntity[]|null
     */
    #[Field('caption_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $captionEntities = null;

    /**
     * Optional. Pass True if the caption must be shown above the message media
     */
    #[Field('show_caption_above_media', required: false)]
    public private(set) ?bool $showCaptionAboveMedia = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    public private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the video animation
     */
    #[Field('input_message_content', required: false)]
    public private(set) ?InputMessageContent $inputMessageContent = null;

}
