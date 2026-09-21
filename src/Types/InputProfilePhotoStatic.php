<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputProfilePhotoType;

/**
 * A static profile photo in the .JPG format.
 *
 * @link https://core.telegram.org/bots/api#inputprofilephotostatic
 */
class InputProfilePhotoStatic extends InputProfilePhoto
{
    /**
     * Type of the profile photo, must be static
     */
    #[Field('type', required: true)]
    public private(set) InputProfilePhotoType|string $type;

    /**
     * The static profile photo. Profile photos can't be reused and can only be uploaded as a new file, so you can pass "attach://<file_attach_name>" if the photo was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('photo', required: true)]
    public private(set) string $photo;

}
