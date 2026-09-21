<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents the content of a media message to be sent. It should be one of
 * - InputMediaAnimation
 * - InputMediaAudio
 * - InputMediaDocument
 * - InputMediaLivePhoto
 * - InputMediaPhoto
 * - InputMediaVideo
 *
 * @link https://core.telegram.org/bots/api#inputmedia
 */
class InputMedia extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {

        return static::class;
    }
}
