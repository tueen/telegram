<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputPaidMediaType;

/**
 * The paid media to send is a photo.
 *
 * @link https://core.telegram.org/bots/api#inputpaidmediaphoto
 */
class InputPaidMediaPhoto extends InputPaidMedia
{
    /**
     * Type of the media, must be photo
     */
    #[Field('type', required: true)]
    public private(set) InputPaidMediaType|string $type;

    /**
     * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('media', required: true)]
    public private(set) string $media;

}
