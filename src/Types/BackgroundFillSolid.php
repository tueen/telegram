<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundFillType;

/**
 * The background is filled using the selected color.
 *
 * @link https://core.telegram.org/bots/api#backgroundfillsolid
 */
class BackgroundFillSolid extends BackgroundFill
{
    /**
     * Type of the background fill, always "solid"
     */
    #[Field('type', required: true)]
    private(set) BackgroundFillType|string $type;

    /**
     * The color of the background fill in the RGB24 format
     */
    #[Field('color', required: true)]
    private(set) int $color;

}
