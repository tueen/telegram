<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object represents the content of a poll option to be sent. It should be one of
 * - InputMediaAnimation
 * - InputMediaLink
 * - InputMediaLivePhoto
 * - InputMediaLocation
 * - InputMediaPhoto
 * - InputMediaSticker
 * - InputMediaVenue
 * - InputMediaVideo
 *
 * @link https://core.telegram.org/bots/api#inputpolloptionmedia
 */
class InputPollOptionMedia extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {

        return static::class;
    }
}
