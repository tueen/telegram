<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\PaidMediaType;
use Tueen\Telegram\Types\LivePhoto;

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
    public private(set) PaidMediaType|string $type;

    /**
     * The photo
     */
    #[Field('live_photo', required: true)]
    public private(set) LivePhoto $livePhoto;

}
