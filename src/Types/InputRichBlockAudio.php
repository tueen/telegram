<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputRichBlockType;
use Tueen\Telegram\Types\InputMediaAudio;
use Tueen\Telegram\Types\RichBlockCaption;

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
    public private(set) InputRichBlockType|string $type;

    /**
     * The audio. Caption is ignored.
     */
    #[Field('audio', required: true)]
    public private(set) InputMediaAudio $audio;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
