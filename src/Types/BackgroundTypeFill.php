<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundTypeType;

/**
 * The background is automatically filled based on the selected colors.
 *
 * @link https://core.telegram.org/bots/api#backgroundtypefill
 */
class BackgroundTypeFill extends BackgroundType
{
    /**
     * Type of the background, always "fill"
     */
    #[Field('type', required: true)]
    private(set) BackgroundTypeType|string|null $type = null;

    /**
     * The background fill
     */
    #[Field('fill', required: true)]
    private(set) ?BackgroundFill $fill = null;

    /**
     * Dimming of the background in dark themes, as a percentage; 0-100
     */
    #[Field('dark_theme_dimming', required: true)]
    private(set) ?int $darkThemeDimming = null;

}
