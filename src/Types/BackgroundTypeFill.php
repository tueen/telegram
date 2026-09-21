<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\BackgroundTypeType;
use Tueen\Telegram\Types\BackgroundFill;

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
    public private(set) BackgroundTypeType|string $type;

    /**
     * The background fill
     */
    #[Field('fill', required: true)]
    public private(set) BackgroundFill $fill;

    /**
     * Dimming of the background in dark themes, as a percentage; 0-100
     */
    #[Field('dark_theme_dimming', required: true)]
    public private(set) int $darkThemeDimming;

}
