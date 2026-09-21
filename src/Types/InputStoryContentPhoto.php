<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputStoryContentType;

/**
 * Describes a photo to post as a story.
 *
 * @link https://core.telegram.org/bots/api#inputstorycontentphoto
 */
class InputStoryContentPhoto extends InputStoryContent
{
    /**
     * Type of the content, must be photo
     */
    #[Field('type', required: true)]
    public private(set) InputStoryContentType|string $type;

    /**
     * The photo to post as a story. The photo must be of the size 1080x1920 and must not exceed 10 MB. The photo can't be reused and can only be uploaded as a new file, so you can pass "attach://<file_attach_name>" if the photo was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('photo', required: true)]
    public private(set) string $photo;

}
