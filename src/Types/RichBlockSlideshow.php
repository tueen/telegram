<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

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
    private(set) RichBlockType|string|null $type = null;

    /**
     * Elements of the slideshow
     * @var RichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(RichBlock::class)]
    private(set) ?array $blocks = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
