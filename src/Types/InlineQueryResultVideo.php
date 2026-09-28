<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents a link to a page containing an embedded video player or a video file. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the video.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvideo
 */
class InlineQueryResultVideo extends InlineQueryResult
{
    /**
     * Type of the result, must be video
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    private(set) string $id;

    /**
     * A valid URL for the embedded video player or video file
     */
    #[Field('video_url', required: true)]
    private(set) string $videoUrl;

    /**
     * MIME type of the content of the video URL, "text/html" or "video/mp4"
     */
    #[Field('mime_type', required: true)]
    private(set) string $mimeType;

    /**
     * URL of the thumbnail (JPEG only) for the video
     */
    #[Field('thumbnail_url', required: true)]
    private(set) string $thumbnailUrl;

    /**
     * Title for the result
     */
    #[Field('title', required: true)]
    private(set) string $title;

    /**
     * Optional. Caption of the video to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the video caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    private(set) ParseMode|string|null $parseMode = null;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var MessageEntity[]|null
     */
    #[Field('caption_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $captionEntities = null;

    /**
     * Optional. Pass True if the caption must be shown above the message media
     */
    #[Field('show_caption_above_media', required: false)]
    private(set) ?bool $showCaptionAboveMedia = null;

    /**
     * Optional. Video width
     */
    #[Field('video_width', required: false)]
    private(set) ?int $videoWidth = null;

    /**
     * Optional. Video height
     */
    #[Field('video_height', required: false)]
    private(set) ?int $videoHeight = null;

    /**
     * Optional. Video duration in seconds
     */
    #[Field('video_duration', required: false)]
    private(set) ?int $videoDuration = null;

    /**
     * Optional. Short description of the result
     */
    #[Field('description', required: false)]
    private(set) ?string $description = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the video. This field is required if InlineQueryResultVideo is used to send an HTML-page as a result (e.g., a YouTube video).
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

}
