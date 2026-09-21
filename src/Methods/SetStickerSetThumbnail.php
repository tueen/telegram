<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Enums\StickerFormat;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Attributes\RequiresUpload;

/**
 * Use this method to set the thumbnail of a regular or mask sticker set. The format of the thumbnail file must match the format of the stickers in the set. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickersetthumbnail
 */
#[ApiMethod('setStickerSetThumbnail', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetStickerSetThumbnail extends Method
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * User identifier of the sticker set owner
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Format of the thumbnail, must be one of "static" for a .WEBP or .PNG image, "animated" for a .TGS animation, or "video" for a .WEBM video
     */
    #[Field('format', required: true)]
    public StickerFormat|string $format;

    /**
     * A .WEBP or .PNG image with the thumbnail, must be up to 128 kilobytes in size and have a width and height of exactly 100px, or a .TGS animation with a thumbnail up to 32 kilobytes in size (see https://core.telegram.org/stickers#animation-requirements for animated sticker technical requirements), or a .WEBM video with the thumbnail up to 32 kilobytes in size; see https://core.telegram.org/stickers#video-requirements for video sticker technical requirements. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. More information on Sending Files: https://core.telegram.org/bots/api#sending-files. Animated and video sticker set thumbnails can't be uploaded via HTTP URL. If omitted, then the thumbnail is dropped and the first sticker is used as the thumbnail.
     */
    #[Field('thumbnail', required: false)]
    #[RequiresUpload]
    public InputFile|string|null $thumbnail = null;

    public function __construct(
        string $name,
        int $userId,
        StickerFormat|string $format,
        InputFile|string|null $thumbnail = null
    )
    {
        if ($name !== null) $this->name = $name;
        if ($userId !== null) $this->userId = $userId;
        if ($format !== null) $this->format = $format;
        if ($thumbnail !== null) $this->thumbnail = $thumbnail;
    }
}
