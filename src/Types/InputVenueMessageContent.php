<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents the content of a venue message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputvenuemessagecontent
 */
class InputVenueMessageContent extends InputMessageContent
{
    /**
     * Latitude of the venue in degrees
     */
    #[Field('latitude', required: true)]
    private(set) ?float $latitude = null;

    /**
     * Longitude of the venue in degrees
     */
    #[Field('longitude', required: true)]
    private(set) ?float $longitude = null;

    /**
     * Name of the venue
     */
    #[Field('title', required: true)]
    private(set) ?string $title = null;

    /**
     * Address of the venue
     */
    #[Field('address', required: true)]
    private(set) ?string $address = null;

    /**
     * Optional. Foursquare identifier of the venue, if known
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
