<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) ?float $xPercentage = null;

    /**
     * The ordinate of the area's center, as a percentage of the media height
     */
    #[Field('y_percentage', required: true)]
    private(set) ?float $yPercentage = null;

    /**
     * The width of the area's rectangle, as a percentage of the media width
     */
    #[Field('width_percentage', required: true)]
    private(set) ?float $widthPercentage = null;

    /**
     * The height of the area's rectangle, as a percentage of the media height
     */
    #[Field('height_percentage', required: true)]
    private(set) ?float $heightPercentage = null;

    /**
     * The clockwise rotation angle of the rectangle, in degrees; 0-360
     */
    #[Field('rotation_angle', required: true)]
    private(set) ?float $rotationAngle = null;

    /**
     * The radius of the rectangle corner rounding, as a percentage of the media width
     */
    #[Field('corner_radius_percentage', required: true)]
    private(set) ?float $cornerRadiusPercentage = null;

}
