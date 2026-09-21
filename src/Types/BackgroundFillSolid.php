<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
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
    public private(set) BackgroundFillType|string $type;

    /**
     * The color of the background fill in the RGB24 format
     */
    #[Field('color', required: true)]
    public private(set) int $color;

}
