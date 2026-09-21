<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes the paid media to be sent. Currently, it can be one of
 * - InputPaidMediaLivePhoto
 * - InputPaidMediaPhoto
 * - InputPaidMediaVideo
 *
 * @link https://core.telegram.org/bots/api#inputpaidmedia
 */
class InputPaidMedia extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {

        return static::class;
    }
}
