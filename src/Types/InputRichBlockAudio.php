<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with a music file, corresponding to the HTML tag <audio>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockaudio
 */
class InputRichBlockAudio extends InputRichBlock
{
    /**
     * Type of the block, always "audio"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * The audio. Caption is ignored.
     */
    #[Field('audio', required: true)]
    private(set) ?InputMediaAudio $audio = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
