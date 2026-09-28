<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with a music file, corresponding to the HTML tag <audio>.
 *
 * @link https://core.telegram.org/bots/api#richblockaudio
 */
class RichBlockAudio extends RichBlock
{
    /**
     * Type of the block, always "audio"
     */
    #[Field('type', required: true)]
    private(set) RichBlockType|string $type;

    /**
     * The audio
     */
    #[Field('audio', required: true)]
    private(set) Audio $audio;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
