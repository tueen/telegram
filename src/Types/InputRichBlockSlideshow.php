<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\InputRichBlock;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A slideshow, corresponding to the custom HTML tag <tg-slideshow>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockslideshow
 */
class InputRichBlockSlideshow extends InputRichBlock
{
    /**
     * Type of the block, always "slideshow"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Elements of the slideshow
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(InputRichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
