<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object describes a profile photo to set. Currently, it can be one of
 * - InputProfilePhotoStatic
 * - InputProfilePhotoAnimated
 *
 * @link https://core.telegram.org/bots/api#inputprofilephoto
 */
class InputProfilePhoto extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {

        return static::class;
    }
}
