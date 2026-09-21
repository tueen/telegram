<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputRichBlockType;
use Tueen\Telegram\Types\InputMediaAnimation;
use Tueen\Telegram\Types\RichBlockCaption;

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
    public private(set) InputRichBlockType|string $type;

    /**
     * The animation. Caption is ignored.
     */
    #[Field('animation', required: true)]
    public private(set) InputMediaAnimation $animation;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
