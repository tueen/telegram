<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents the content of a location message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputlocationmessagecontent
 */
class InputLocationMessageContent extends InputMessageContent
{
    /**
     * Latitude of the location in degrees
     */
    #[Field('latitude', required: true)]
    private(set) float $latitude;

    /**
     * Longitude of the location in degrees
     */
    #[Field('longitude', required: true)]
    private(set) float $longitude;

    /**
     * Optional. The radius of uncertainty for the location, measured in meters; 0-1500
     */
    #[Field('horizontal_accuracy', required: false)]
    private(set) ?float $horizontalAccuracy = null;

    /**
     * Optional. Period in seconds during which the location can be updated, must be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely
     */
    #[Field('live_period', required: false)]
    private(set) ?int $livePeriod = null;

    /**
     * Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
     */
    #[Field('heading', required: false)]
    private(set) ?int $heading = null;

    /**
     * Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
     */
    #[Field('proximity_alert_radius', required: false)]
    private(set) ?int $proximityAlertRadius = null;

}
