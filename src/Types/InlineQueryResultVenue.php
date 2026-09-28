<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;

/**
 * Represents a venue. By default, the venue will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the venue.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvenue
 */
class InlineQueryResultVenue extends InlineQueryResult
{
    /**
     * Type of the result, must be venue
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string|null $type = null;

    /**
     * Unique identifier for this result, 1-64 Bytes
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Latitude of the venue location in degrees
     */
    #[Field('latitude', required: true)]
    private(set) ?float $latitude = null;

    /**
     * Longitude of the venue location in degrees
     */
    #[Field('longitude', required: true)]
    private(set) ?float $longitude = null;

    /**
     * Title of the venue
     */
    #[Field('title', required: true)]
    private(set) ?string $title = null;

    /**
     * Address of the venue
     */
    #[Field('address', required: true)]
    private(set) ?string $address = null;

    /**
     * Optional. Foursquare identifier of the venue if known
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

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the venue
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
