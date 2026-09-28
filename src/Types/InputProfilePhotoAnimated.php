<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputProfilePhotoType;

/**
 * An animated profile photo in the MPEG4 format.
 *
 * @link https://core.telegram.org/bots/api#inputprofilephotoanimated
 */
class InputProfilePhotoAnimated extends InputProfilePhoto
{
    /**
     * Type of the profile photo, must be animated
     */
    #[Field('type', required: true)]
    private(set) InputProfilePhotoType|string|null $type = null;

    /**
     * The animated profile photo. Profile photos can't be reused and can only be uploaded as a new file, so you can pass "attach://<file_attach_name>" if the photo was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('animation', required: true)]
    private(set) ?string $animation = null;

    /**
     * Optional. Timestamp in seconds of the frame that will be used as the static profile photo. Defaults to 0.0.
     */
    #[Field('main_frame_timestamp', required: false)]
    private(set) ?float $mainFrameTimestamp = null;

}
