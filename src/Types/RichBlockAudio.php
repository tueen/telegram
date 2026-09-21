<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichBlockType;
use Tueen\Telegram\Types\Audio;
use Tueen\Telegram\Types\RichBlockCaption;

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
    public private(set) RichBlockType|string $type;

    /**
     * The audio
     */
    #[Field('audio', required: true)]
    public private(set) Audio $audio;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
