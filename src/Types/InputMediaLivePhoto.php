<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputMediaType;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Types\MessageEntity;

/**
 * Represents a live photo to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmedialivephoto
 */
class InputMediaLivePhoto extends InputPollMedia
{
    /**
     * Type of the media, must be live_photo
     */
    #[Field('type', required: true)]
    public private(set) InputMediaType|string $type;

    /**
     * Video of the live photo to send. Pass a file_id to send a file that exists on the Telegram servers (recommended) or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files. Sending live photos by a URL is currently unsupported.
     */
    #[Field('media', required: true)]
    public private(set) string $media;

    /**
     * The static photo to send. Pass a file_id to send a file that exists on the Telegram servers (recommended) or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files. Sending live photos by a URL is currently unsupported.
     */
    #[Field('photo', required: true)]
    public private(set) string $photo;

    /**
     * Optional. Caption of the live photo to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the live photo caption. See formatting options for more details.
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
     * Optional. Pass True if the live photo needs to be covered with a spoiler animation
     */
    #[Field('has_spoiler', required: false)]
    public private(set) ?bool $hasSpoiler = null;

}
