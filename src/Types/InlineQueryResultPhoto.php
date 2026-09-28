<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Represents a link to a photo. By default, this photo will be sent by the user with optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the photo.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultphoto
 */
class InlineQueryResultPhoto extends InlineQueryResult
{
    /**
     * Type of the result, must be photo
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 bytes
     */
    #[Field('id', required: true)]
    private(set) string $id;

    /**
     * A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB.
     */
    #[Field('photo_url', required: true)]
    private(set) string $photoUrl;

    /**
     * URL of the thumbnail for the photo
     */
    #[Field('thumbnail_url', required: true)]
    private(set) string $thumbnailUrl;

    /**
     * Optional. Width of the photo
     */
    #[Field('photo_width', required: false)]
    private(set) ?int $photoWidth = null;

    /**
     * Optional. Height of the photo
     */
    #[Field('photo_height', required: false)]
    private(set) ?int $photoHeight = null;

    /**
     * Optional. Title for the result
     */
    #[Field('title', required: false)]
    private(set) ?string $title = null;

    /**
     * Optional. Short description of the result
     */
    #[Field('description', required: false)]
    private(set) ?string $description = null;

    /**
     * Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the photo caption. See formatting options for more details.
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
     * Optional. Content of the message to be sent instead of the photo
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

}
