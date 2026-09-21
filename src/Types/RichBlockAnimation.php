<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichBlockType;
use Tueen\Telegram\Types\Animation;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A block with an animation, corresponding to the HTML tag <video>.
 *
 * @link https://core.telegram.org/bots/api#richblockanimation
 */
class RichBlockAnimation extends RichBlock
{
    /**
     * Type of the block, always "animation"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * The animation
     */
    #[Field('animation', required: true)]
    public private(set) Animation $animation;

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
