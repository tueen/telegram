<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichBlock;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A slideshow, corresponding to the custom HTML tag <tg-slideshow>.
 *
 * @link https://core.telegram.org/bots/api#richblockslideshow
 */
class RichBlockSlideshow extends RichBlock
{
    /**
     * Type of the block, always "slideshow"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Elements of the slideshow
     * @var RichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(RichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
