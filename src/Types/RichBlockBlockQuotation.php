<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block quotation, corresponding to the HTML tag <blockquote>.
 *
 * @link https://core.telegram.org/bots/api#richblockblockquotation
 */
class RichBlockBlockQuotation extends RichBlock
{
    /**
     * Type of the block, always "blockquote"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Content of the block
     * @var RichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(RichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. Credit of the block
     */
    #[Field('credit', required: false)]
    public private(set) ?RichText $credit = null;

}
