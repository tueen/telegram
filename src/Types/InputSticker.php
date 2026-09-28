<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StickerFormat;

/**
 * This object describes a sticker to be added to a sticker set.
 *
 * @link https://core.telegram.org/bots/api#inputsticker
 */
class InputSticker extends Type
{
    /**
     * The added sticker. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass an HTTP URL as a String for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new file using multipart/form-data under <file_attach_name> name. Animated and video stickers can't be uploaded via HTTP URL. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('sticker', required: true)]
    private(set) string $sticker;

    /**
     * Format of the added sticker, must be one of "static" for a .WEBP or .PNG image, "animated" for a .TGS animation, "video" for a .WEBM video
     */
    #[Field('format', required: true)]
    private(set) StickerFormat|string $format;

    /**
     * List of 1-20 emoji associated with the sticker
     * @var String[]|null
     */
    #[Field('emoji_list', required: true)]
    private(set) array $emojiList;

    /**
     * Optional. Position where the mask should be placed on faces. For "mask" stickers only.
     */
    #[Field('mask_position', required: false)]
    private(set) ?MaskPosition $maskPosition = null;

    /**
     * Optional. List of 0-20 search keywords for the sticker with total length of up to 64 characters. For "regular" and "custom_emoji" stickers only.
     * @var String[]|null
     */
    #[Field('keywords', required: false)]
    private(set) ?array $keywords = null;

}
