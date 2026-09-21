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
    public private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * A valid file identifier for the video file
     */
    #[Field('video_file_id', required: true)]
    public private(set) string $videoFileId;

    /**
     * Title for the result
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Optional. Short description of the result
     */
    #[Field('description', required: false)]
    public private(set) ?string $description = null;

    /**
     * Optional. Caption of the video to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the video caption. See formatting options for more details.
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
     * Optional. Content of the message to be sent instead of the video
     */
    #[Field('input_message_content', required: false)]
    public private(set) ?InputMessageContent $inputMessageContent = null;

}
