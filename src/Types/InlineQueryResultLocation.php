<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;

/**
 * Represents a location on a map. By default, the location will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the location.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultlocation
 */
class InlineQueryResultLocation extends InlineQueryResult
{
    /**
     * Type of the result, must be location
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 Bytes
     */
    #[Field('id', required: true)]
    private(set) string $id;

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
     * Location title
     */
    #[Field('title', required: true)]
    private(set) string $title;

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

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the location
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

    /**
     * Optional. Url of the thumbnail for the result
     */
    #[Field('thumbnail_url', required: false)]
    private(set) ?string $thumbnailUrl = null;

    /**
     * Optional. Thumbnail width
     */
    #[Field('thumbnail_width', required: false)]
    private(set) ?int $thumbnailWidth = null;

    /**
     * Optional. Thumbnail height
     */
    #[Field('thumbnail_height', required: false)]
    private(set) ?int $thumbnailHeight = null;

}
