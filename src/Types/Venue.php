<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a venue.
 *
 * @link https://core.telegram.org/bots/api#venue
 */
class Venue extends Type
{
    /**
     * Venue location. Can't be a live location.
     */
    #[Field('location', required: true)]
    private(set) ?Location $location = null;

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
     * Optional. Foursquare identifier of the venue
     */
    #[Field('foursquare_id', required: false)]
    private(set) ?string $foursquareId = null;

    /**
     * Optional. Foursquare type of the venue. (For example, "arts_entertainment/default", "arts_entertainment/aquarium" or "food/icecream".)
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
