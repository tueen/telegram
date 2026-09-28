<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundFillType;

/**
 * The background is a gradient fill.
 *
 * @link https://core.telegram.org/bots/api#backgroundfillgradient
 */
class BackgroundFillGradient extends BackgroundFill
{
    /**
     * Type of the background fill, always "gradient"
     */
    #[Field('type', required: true)]
    private(set) BackgroundFillType|string $type;

    /**
     * Top color of the gradient in the RGB24 format
     */
    #[Field('top_color', required: true)]
    private(set) int $topColor;

    /**
     * Bottom color of the gradient in the RGB24 format
     */
    #[Field('bottom_color', required: true)]
    private(set) int $bottomColor;

    /**
     * Clockwise rotation angle of the background fill in degrees; 0-359
     */
    #[Field('rotation_angle', required: true)]
    private(set) int $rotationAngle;

}
