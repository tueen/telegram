<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Represents a sticker file to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediasticker
 */
class InputMediaSticker extends InputPollOptionMedia
{
    /**
     * Type of the media, must be sticker
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a .WEBP sticker from the Internet, or pass "attach://<file_attach_name>" to upload a new .WEBP, .TGS, or .WEBM sticker using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('media', required: true)]
    public private(set) string $media;

    /**
     * Optional. Emoji associated with the sticker; only for just uploaded stickers
     */
    #[Field('emoji', required: false)]
    public private(set) ?string $emoji = null;

}
