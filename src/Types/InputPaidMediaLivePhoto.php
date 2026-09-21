<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * The paid media to send is a live photo.
 *
 * @link https://core.telegram.org/bots/api#inputpaidmedialivephoto
 */
class InputPaidMediaLivePhoto extends InputPaidMedia
{
    /**
     * Type of the media, must be live_photo
     */
    #[Field('type', required: true)]
    public private(set) string $type;

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

}
