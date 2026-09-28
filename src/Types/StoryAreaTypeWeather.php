<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) StoryAreaTypeType|string|null $type = null;

    /**
     * Temperature, in degree Celsius
     */
    #[Field('temperature', required: true)]
    private(set) ?float $temperature = null;

    /**
     * Emoji representing the weather
     */
    #[Field('emoji', required: true)]
    private(set) ?string $emoji = null;

    /**
     * A color of the area background in the ARGB format
     */
    #[Field('background_color', required: true)]
    private(set) ?int $backgroundColor = null;

}
