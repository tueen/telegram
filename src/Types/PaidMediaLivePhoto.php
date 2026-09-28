<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PaidMediaType;

/**
 * The paid media is a live photo.
 *
 * @link https://core.telegram.org/bots/api#paidmedialivephoto
 */
class PaidMediaLivePhoto extends PaidMedia
{
    /**
     * Type of the paid media, always "live_photo"
     */
    #[Field('type', required: true)]
    private(set) PaidMediaType|string|null $type = null;

    /**
     * The photo
     */
    #[Field('live_photo', required: true)]
    private(set) ?LivePhoto $livePhoto = null;

}
