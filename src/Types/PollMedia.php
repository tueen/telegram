<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

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
    private(set) ?Animation $animation = null;

    /**
     * Optional. Media is an audio file, information about the file; currently, can't be received in a poll option
     */
    #[Field('audio', required: false)]
    private(set) ?Audio $audio = null;

    /**
     * Optional. Media is a general file, information about the file; currently, can't be received in a poll option
     */
    #[Field('document', required: false)]
    private(set) ?Document $document = null;

    /**
     * Optional. The HTTP link attached to the poll option
     */
    #[Field('link', required: false)]
    private(set) ?Link $link = null;

    /**
     * Optional. Media is a live photo, information about the live photo
     */
    #[Field('live_photo', required: false)]
    private(set) ?LivePhoto $livePhoto = null;

    /**
     * Optional. Media is a shared location, information about the location
     */
    #[Field('location', required: false)]
    private(set) ?Location $location = null;

    /**
     * Optional. Media is a photo, available sizes of the photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    private(set) ?array $photo = null;

    /**
     * Optional. Media is a sticker, information about the sticker; currently, for poll options only
     */
    #[Field('sticker', required: false)]
    private(set) ?Sticker $sticker = null;

    /**
     * Optional. Media is a venue, information about the venue
     */
    #[Field('venue', required: false)]
    private(set) ?Venue $venue = null;

    /**
     * Optional. Media is a video, information about the video
     */
    #[Field('video', required: false)]
    private(set) ?Video $video = null;

}
