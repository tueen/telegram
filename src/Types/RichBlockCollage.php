<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

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
    private(set) RichBlockType|string|null $type = null;

    /**
     * Elements of the collage
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
