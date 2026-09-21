<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with a video, corresponding to the HTML tag <video>.
 *
 * @link https://core.telegram.org/bots/api#richblockvideo
 */
class RichBlockVideo extends RichBlock
{
    /**
     * Type of the block, always "video"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * The video
     */
    #[Field('video', required: true)]
    public private(set) Video $video;

    /**
     * Optional. True, if the media preview is covered by a spoiler animation
     */
    #[Field('has_spoiler', required: false)]
    public private(set) ?bool $hasSpoiler = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
