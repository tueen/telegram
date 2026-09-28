<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents a link to a video file stored on the Telegram servers. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the video.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedvideo
 */
class InlineQueryResultCachedVideo extends InlineQueryResult
{
    /**
     * Type of the result, must be video
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string|null $type = null;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * A valid file identifier for the video file
     */
    #[Field('video_file_id', required: true)]
    private(set) ?string $videoFileId = null;

    /**
     * Title for the result
     */
    #[Field('title', required: true)]
    private(set) ?string $title = null;

    /**
     * Optional. Short description of the result
     */
    #[Field('description', required: false)]
    private(set) ?string $description = null;

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
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the video
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

}
