<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\PaidMediaType;
use Tueen\Telegram\Types\Video;

/**
 * The paid media is a video.
 *
 * @link https://core.telegram.org/bots/api#paidmediavideo
 */
class PaidMediaVideo extends PaidMedia
{
    /**
     * Type of the paid media, always "video"
     */
    #[Field('type', required: true)]
    public private(set) PaidMediaType|string $type;

    /**
     * The video
     */
    #[Field('video', required: true)]
    public private(set) Video $video;

}
