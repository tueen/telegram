<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

/**
 * This object represents the content of a poll description or a quiz explanation to be sent. It should be one of
 * - InputMediaAnimation
 * - InputMediaAudio
 * - InputMediaDocument
 * - InputMediaLivePhoto
 * - InputMediaLocation
 * - InputMediaPhoto
 * - InputMediaVenue
 * - InputMediaVideo
 *
 * @link https://core.telegram.org/bots/api#inputpollmedia
 */
class InputPollMedia extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {

        return static::class;
    }
}
