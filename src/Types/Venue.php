<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
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
    public private(set) Location $location;

    /**
     * Name of the venue
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Address of the venue
     */
    #[Field('address', required: true)]
    public private(set) string $address;

    /**
     * Optional. Foursquare identifier of the venue
     */
    #[Field('foursquare_id', required: false)]
    public private(set) ?string $foursquareId = null;

    /**
     * Optional. Foursquare type of the venue. (For example, "arts_entertainment/default", "arts_entertainment/aquarium" or "food/icecream".)
     */
    #[Field('foursquare_type', required: false)]
    public private(set) ?string $foursquareType = null;

    /**
     * Optional. Google Places identifier of the venue
     */
    #[Field('google_place_id', required: false)]
    public private(set) ?string $googlePlaceId = null;

    /**
     * Optional. Google Places type of the venue. (See supported types.)
     */
    #[Field('google_place_type', required: false)]
    public private(set) ?string $googlePlaceType = null;

}
