<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with a video, corresponding to the HTML tag <video>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockvideo
 */
class InputRichBlockVideo extends InputRichBlock
{
    /**
     * Type of the block, always "video"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * The video. Caption is ignored.
     */
    #[Field('video', required: true)]
    private(set) ?InputMediaVideo $video = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
