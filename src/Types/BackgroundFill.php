<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the way a background is filled based on the selected colors. Currently, it can be one of
 * - BackgroundFillSolid
 * - BackgroundFillGradient
 * - BackgroundFillFreeformGradient
 *
 * @link https://core.telegram.org/bots/api#backgroundfill
 */
class BackgroundFill extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'solid') return BackgroundFillSolid::class;
        if (($data['type'] ?? '') === 'gradient') return BackgroundFillGradient::class;
        if (($data['type'] ?? '') === 'freeform_gradient') return BackgroundFillFreeformGradient::class;
        return static::class;
    }
}
