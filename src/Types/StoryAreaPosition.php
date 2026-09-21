<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes the position of a clickable area within a story.
 *
 * @link https://core.telegram.org/bots/api#storyareaposition
 */
class StoryAreaPosition extends Type
{
    /**
     * The abscissa of the area's center, as a percentage of the media width
     */
    #[Field('x_percentage', required: true)]
    public private(set) float $xPercentage;

    /**
     * The ordinate of the area's center, as a percentage of the media height
     */
    #[Field('y_percentage', required: true)]
    public private(set) float $yPercentage;

    /**
     * The width of the area's rectangle, as a percentage of the media width
     */
    #[Field('width_percentage', required: true)]
    public private(set) float $widthPercentage;

    /**
     * The height of the area's rectangle, as a percentage of the media height
     */
    #[Field('height_percentage', required: true)]
    public private(set) float $heightPercentage;

    /**
     * The clockwise rotation angle of the rectangle, in degrees; 0-360
     */
    #[Field('rotation_angle', required: true)]
    public private(set) float $rotationAngle;

    /**
     * The radius of the rectangle corner rounding, as a percentage of the media width
     */
    #[Field('corner_radius_percentage', required: true)]
    public private(set) float $cornerRadiusPercentage;

}
