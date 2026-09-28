<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents a link to an animated GIF file. By default, this animated GIF file will be sent by the user with optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the animation.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultgif
 */
class InlineQueryResultGif extends InlineQueryResult
{
    /**
     * Type of the result, must be gif
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string|null $type = null;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * A valid URL for the GIF file
     */
    #[Field('gif_url', required: true)]
    private(set) ?string $gifUrl = null;

    /**
     * Optional. Width of the GIF
     */
    #[Field('gif_width', required: false)]
    private(set) ?int $gifWidth = null;

    /**
     * Optional. Height of the GIF
     */
    #[Field('gif_height', required: false)]
    private(set) ?int $gifHeight = null;

    /**
     * Optional. Duration of the GIF in seconds
     */
    #[Field('gif_duration', required: false)]
    private(set) ?int $gifDuration = null;

    /**
     * URL of the static (JPEG or GIF) or animated (MPEG4) thumbnail for the result
     */
    #[Field('thumbnail_url', required: true)]
    private(set) ?string $thumbnailUrl = null;

    /**
     * Optional. MIME type of the thumbnail, must be one of "image/jpeg", "image/gif", or "video/mp4". Defaults to "image/jpeg".
     */
    #[Field('thumbnail_mime_type', required: false)]
    private(set) ?string $thumbnailMimeType = null;

    /**
     * Optional. Title for the result
     */
    #[Field('title', required: false)]
    private(set) ?string $title = null;

    /**
     * Optional. Caption of the GIF file to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the caption. See formatting options for more details.
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
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the GIF animation
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

}
