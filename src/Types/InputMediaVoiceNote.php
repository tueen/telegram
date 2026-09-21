<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\MessageEntity;

/**
 * Represents a voice message file to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediavoicenote
 */
class InputMediaVoiceNote extends Type
{
    /**
     * Type of the media, must be voice_note
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('media', required: true)]
    public private(set) string $media;

    /**
     * Optional. Caption of the voice message to be sent, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. Mode for parsing entities in the voice message caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public private(set) ?string $parseMode = null;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var MessageEntity[]|null
     */
    #[Field('caption_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $captionEntities = null;

    /**
     * Optional. Duration of the voice message in seconds
     */
    #[Field('duration', required: false)]
    public private(set) ?int $duration = null;

}
