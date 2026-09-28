<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a chat photo.
 *
 * @link https://core.telegram.org/bots/api#chatphoto
 */
class ChatPhoto extends Type
{
    /**
     * File identifier of small (160x160) chat photo. This file_id can be used only for photo download and only for as long as the photo is not changed.
     */
    #[Field('small_file_id', required: true)]
    private(set) ?string $smallFileId = null;

    /**
     * Unique file identifier of small (160x160) chat photo, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     */
    #[Field('small_file_unique_id', required: true)]
    private(set) ?string $smallFileUniqueId = null;

    /**
     * File identifier of big (640x640) chat photo. This file_id can be used only for photo download and only for as long as the photo is not changed.
     */
    #[Field('big_file_id', required: true)]
    private(set) ?string $bigFileId = null;

    /**
     * Unique file identifier of big (640x640) chat photo, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     */
    #[Field('big_file_unique_id', required: true)]
    private(set) ?string $bigFileUniqueId = null;

}
