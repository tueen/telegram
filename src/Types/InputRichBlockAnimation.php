<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with an animation, corresponding to the HTML tag <video>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockanimation
 */
class InputRichBlockAnimation extends InputRichBlock
{
    /**
     * Type of the block, always "animation"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string $type;

    /**
     * The animation. Caption is ignored.
     */
    #[Field('animation', required: true)]
    private(set) InputMediaAnimation $animation;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
