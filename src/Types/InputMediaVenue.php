<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputMediaType;

/**
 * Represents a venue to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputmediavenue
 */
class InputMediaVenue extends InputPollMedia
{
    /**
     * Type of the media, must be venue
     */
    #[Field('type', required: true)]
    private(set) InputMediaType|string $type;

    /**
     * Latitude of the location
     */
    #[Field('latitude', required: true)]
    private(set) float $latitude;

    /**
     * Longitude of the location
     */
    #[Field('longitude', required: true)]
    private(set) float $longitude;

    /**
     * Name of the venue
     */
    #[Field('title', required: true)]
    private(set) string $title;

    /**
     * Address of the venue
     */
    #[Field('address', required: true)]
    private(set) string $address;

    /**
     * Optional. Foursquare identifier of the venue
     */
    #[Field('foursquare_id', required: false)]
    private(set) ?string $foursquareId = null;

    /**
     * Optional. Foursquare type of the venue, if known. (For example, "arts_entertainment/default", "arts_entertainment/aquarium" or "food/icecream".)
     */
    #[Field('foursquare_type', required: false)]
    private(set) ?string $foursquareType = null;

    /**
     * Optional. Google Places identifier of the venue
     */
    #[Field('google_place_id', required: false)]
    private(set) ?string $googlePlaceId = null;

    /**
     * Optional. Google Places type of the venue. (See supported types.)
     */
    #[Field('google_place_type', required: false)]
    private(set) ?string $googlePlaceType = null;

}
