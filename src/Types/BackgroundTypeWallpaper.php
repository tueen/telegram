<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundTypeType;

/**
 * The background is a wallpaper in the JPEG format.
 *
 * @link https://core.telegram.org/bots/api#backgroundtypewallpaper
 */
class BackgroundTypeWallpaper extends BackgroundType
{
    /**
     * Type of the background, always "wallpaper"
     */
    #[Field('type', required: true)]
    private(set) BackgroundTypeType|string $type;

    /**
     * Document with the wallpaper
     */
    #[Field('document', required: true)]
    private(set) Document $document;

    /**
     * Dimming of the background in dark themes, as a percentage; 0-100
     */
    #[Field('dark_theme_dimming', required: true)]
    private(set) int $darkThemeDimming;

    /**
     * Optional. True, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
     */
    #[Field('is_blurred', required: false)]
    private(set) ?bool $isBlurred = null;

    /**
     * Optional. True, if the background moves slightly when the device is tilted
     */
    #[Field('is_moving', required: false)]
    private(set) ?bool $isMoving = null;

}
