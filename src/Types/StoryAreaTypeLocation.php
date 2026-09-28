<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\StoryAreaTypeType;

/**
 * Describes a story area pointing to a location. Currently, a story can have up to 10 location areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypelocation
 */
class StoryAreaTypeLocation extends StoryAreaType
{
    /**
     * Type of the area, always "location"
     */
    #[Field('type', required: true)]
    private(set) StoryAreaTypeType|string $type;

    /**
     * Location latitude in degrees
     */
    #[Field('latitude', required: true)]
    private(set) float $latitude;

    /**
     * Location longitude in degrees
     */
    #[Field('longitude', required: true)]
    private(set) float $longitude;

    /**
     * Optional. Address of the location
     */
    #[Field('address', required: false)]
    private(set) ?LocationAddress $address = null;

}
