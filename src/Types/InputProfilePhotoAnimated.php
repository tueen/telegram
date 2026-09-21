<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
    public private(set) string $type;

    /**
     * The animated profile photo. Profile photos can't be reused and can only be uploaded as a new file, so you can pass "attach://<file_attach_name>" if the photo was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('animation', required: true)]
    public private(set) string $animation;

    /**
     * Optional. Timestamp in seconds of the frame that will be used as the static profile photo. Defaults to 0.0.
     */
    #[Field('main_frame_timestamp', required: false)]
    public private(set) ?float $mainFrameTimestamp = null;

}
