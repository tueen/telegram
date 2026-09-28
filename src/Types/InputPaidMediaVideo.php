<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputPaidMediaType;

/**
 * The paid media to send is a video.
 *
 * @link https://core.telegram.org/bots/api#inputpaidmediavideo
 */
class InputPaidMediaVideo extends InputPaidMedia
{
    /**
     * Type of the media, must be video
     */
    #[Field('type', required: true)]
    private(set) InputPaidMediaType|string|null $type = null;

    /**
     * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('media', required: true)]
    private(set) ?string $media = null;

    /**
     * Optional. Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('thumbnail', required: false)]
    private(set) ?string $thumbnail = null;

    /**
     * Optional. Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('cover', required: false)]
    private(set) ?string $cover = null;

    /**
     * Optional. Start timestamp for the video in the message
     */
    #[Field('start_timestamp', required: false)]
    private(set) ?int $startTimestamp = null;

    /**
     * Optional. Video width
     */
    #[Field('width', required: false)]
    private(set) ?int $width = null;

    /**
     * Optional. Video height
     */
    #[Field('height', required: false)]
    private(set) ?int $height = null;

    /**
     * Optional. Video duration in seconds
     */
    #[Field('duration', required: false)]
    private(set) ?int $duration = null;

    /**
     * Optional. Pass True if the uploaded video is suitable for streaming
     */
    #[Field('supports_streaming', required: false)]
    private(set) ?bool $supportsStreaming = null;

}
