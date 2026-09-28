<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes a media element embedded in an outgoing rich message.
 *
 * @link https://core.telegram.org/bots/api#inputrichmessagemedia
 */
class InputRichMessageMedia extends Type
{
    /**
     * Unique identifier of the media used in a tg://photo?id=, tg://video?id=, tg://document?id=, or tg://audio?id= link. 1-64 characters, only A-Z, a-z, 0-9, _ and - are allowed.
     */
    #[Field('id', required: true)]
    private(set) string $id;

    /**
     * The media to be sent. Everything except the media itself and its properties is ignored.
     */
    #[Field('media', required: true)]
    private(set) InputMediaAnimation|InputMediaAudio|InputMediaDocument|InputMediaPhoto|InputMediaVideo|InputMediaVoiceNote $media;

}
