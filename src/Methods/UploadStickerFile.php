<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\File;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Enums\StickerFormat;

/**
 * Use this method to upload a file with a sticker for later use in the createNewStickerSet, addStickerToSet, or replaceStickerInSet methods (the file can be used multiple times). Returns the uploaded File on success.
 *
 * @link https://core.telegram.org/bots/api#uploadstickerfile
 */
#[ApiMethod('uploadStickerFile', 'POST')]
#[ReturnType(File::class, isArray: false)]
class UploadStickerFile extends Method
{
    /**
     * User identifier of sticker file owner
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * A file with the sticker in .WEBP, .PNG, .TGS, or .WEBM format. See https://core.telegram.org/stickers for technical requirements. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('sticker', required: true)]
    #[RequiresUpload]
    public InputFile $sticker;

    /**
     * Format of the sticker, must be one of "static", "animated", "video"
     */
    #[Field('sticker_format', required: true)]
    public StickerFormat|string $stickerFormat;

    public function __construct(
        int $userId,
        InputFile $sticker,
        StickerFormat|string $stickerFormat
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($sticker !== null) $this->sticker = $sticker;
        if ($stickerFormat !== null) $this->stickerFormat = $stickerFormat;
    }

    public static function make(
        int $userId,
        InputFile $sticker,
        StickerFormat|string $stickerFormat
    ): static
    {
        return new static($userId, $sticker, $stickerFormat);
    }
}
