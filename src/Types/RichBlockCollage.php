<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichBlockType;
use Tueen\Telegram\Types\RichBlock;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A collage, corresponding to the custom HTML tag <tg-collage>.
 *
 * @link https://core.telegram.org/bots/api#richblockcollage
 */
class RichBlockCollage extends RichBlock
{
    /**
     * Type of the block, always "collage"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Elements of the collage
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
