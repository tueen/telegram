<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputMediaType;

/**
 * Represents a location to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmedialocation
 */
class InputMediaLocation extends InputPollMedia
{
    /**
     * Type of the media, must be location
     */
    #[Field('type', required: true)]
    public private(set) InputMediaType|string $type;

    /**
     * Latitude of the location
     */
    #[Field('latitude', required: true)]
    public private(set) float $latitude;

    /**
     * Longitude of the location
     */
    #[Field('longitude', required: true)]
    public private(set) float $longitude;

    /**
     * Optional. The radius of uncertainty for the location, measured in meters; 0-1500
     */
    #[Field('horizontal_accuracy', required: false)]
    public private(set) ?float $horizontalAccuracy = null;

}
