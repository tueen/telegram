<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputStoryContentType;

/**
 * Describes a video to post as a story.
 *
 * @link https://core.telegram.org/bots/api#inputstorycontentvideo
 */
class InputStoryContentVideo extends InputStoryContent
{
    /**
     * Type of the content, must be video
     */
    #[Field('type', required: true)]
    private(set) InputStoryContentType|string|null $type = null;

    /**
     * The video to post as a story. The video must be of the size 720x1280, streamable, encoded with H.265 codec, with key frames added each second in the MPEG4 format, and must not exceed 30 MB. The video can't be reused and can only be uploaded as a new file, so you can pass "attach://<file_attach_name>" if the video was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('video', required: true)]
    private(set) ?string $video = null;

    /**
     * Optional. Precise duration of the video in seconds; 0-60
     */
    #[Field('duration', required: false)]
    private(set) ?float $duration = null;

    /**
     * Optional. Timestamp in seconds of the frame that will be used as the static cover for the story. Defaults to 0.0.
     */
    #[Field('cover_frame_timestamp', required: false)]
    private(set) ?float $coverFrameTimestamp = null;

    /**
     * Optional. Pass True if the video has no sound
     */
    #[Field('is_animation', required: false)]
    private(set) ?bool $isAnimation = null;

}
