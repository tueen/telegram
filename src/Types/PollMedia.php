<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Animation;
use Tueen\Telegram\Types\Audio;
use Tueen\Telegram\Types\Document;
use Tueen\Telegram\Types\Link;
use Tueen\Telegram\Types\LivePhoto;
use Tueen\Telegram\Types\Location;
use Tueen\Telegram\Types\PhotoSize;
use Tueen\Telegram\Types\Sticker;
use Tueen\Telegram\Types\Venue;
use Tueen\Telegram\Types\Video;

/**
 * At most one of the optional fields can be present in any given object.
 *
 * @link https://core.telegram.org/bots/api#pollmedia
 */
class PollMedia extends Type
{
    /**
     * Optional. Media is an animation, information about the animation
     */
    #[Field('animation', required: false)]
    public private(set) ?Animation $animation = null;

    /**
     * Optional. Media is an audio file, information about the file; currently, can't be received in a poll option
     */
    #[Field('audio', required: false)]
    public private(set) ?Audio $audio = null;

    /**
     * Optional. Media is a general file, information about the file; currently, can't be received in a poll option
     */
    #[Field('document', required: false)]
    public private(set) ?Document $document = null;

    /**
     * Optional. The HTTP link attached to the poll option
     */
    #[Field('link', required: false)]
    public private(set) ?Link $link = null;

    /**
     * Optional. Media is a live photo, information about the live photo
     */
    #[Field('live_photo', required: false)]
    public private(set) ?LivePhoto $livePhoto = null;

    /**
     * Optional. Media is a shared location, information about the location
     */
    #[Field('location', required: false)]
    public private(set) ?Location $location = null;

    /**
     * Optional. Media is a photo, available sizes of the photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) ?array $photo = null;

    /**
     * Optional. Media is a sticker, information about the sticker; currently, for poll options only
     */
    #[Field('sticker', required: false)]
    public private(set) ?Sticker $sticker = null;

    /**
     * Optional. Media is a venue, information about the venue
     */
    #[Field('venue', required: false)]
    public private(set) ?Venue $venue = null;

    /**
     * Optional. Media is a video, information about the video
     */
    #[Field('video', required: false)]
    public private(set) ?Video $video = null;

}
