<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a point on the map.
 *
 * @link https://core.telegram.org/bots/api#location
 */
class Location extends Type
{
    /**
     * Latitude as defined by the sender
     */
    #[Field('latitude', required: true)]
    public private(set) float $latitude;

    /**
     * Longitude as defined by the sender
     */
    #[Field('longitude', required: true)]
    public private(set) float $longitude;

    /**
     * Optional. The radius of uncertainty for the location, measured in meters; 0-1500
     */
    #[Field('horizontal_accuracy', required: false)]
    public private(set) ?float $horizontalAccuracy = null;

    /**
     * Optional. Time relative to the message sending date, during which the location can be updated; in seconds. For active live locations only.
     */
    #[Field('live_period', required: false)]
    public private(set) ?int $livePeriod = null;

    /**
     * Optional. The direction in which user is moving, in degrees; 1-360. For active live locations only.
     */
    #[Field('heading', required: false)]
    public private(set) ?int $heading = null;

    /**
     * Optional. The maximum distance for proximity alerts about approaching another chat member, in meters. For sent live locations only.
     */
    #[Field('proximity_alert_radius', required: false)]
    public private(set) ?int $proximityAlertRadius = null;

}
