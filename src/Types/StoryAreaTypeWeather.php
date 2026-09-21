<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StoryAreaTypeType;

/**
 * Describes a story area containing weather information. Currently, a story can have up to 3 weather areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypeweather
 */
class StoryAreaTypeWeather extends StoryAreaType
{
    /**
     * Type of the area, always "weather"
     */
    #[Field('type', required: true)]
    public private(set) StoryAreaTypeType|string $type;

    /**
     * Temperature, in degree Celsius
     */
    #[Field('temperature', required: true)]
    public private(set) float $temperature;

    /**
     * Emoji representing the weather
     */
    #[Field('emoji', required: true)]
    public private(set) string $emoji;

    /**
     * A color of the area background in the ARGB format
     */
    #[Field('background_color', required: true)]
    public private(set) int $backgroundColor;

}
