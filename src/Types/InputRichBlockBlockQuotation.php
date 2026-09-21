<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\InputRichBlock;
use Tueen\Telegram\Types\RichText;

/**
 * A block quotation, corresponding to the HTML tag <blockquote>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockblockquotation
 */
class InputRichBlockBlockQuotation extends InputRichBlock
{
    /**
     * Type of the block, always "blockquote"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Content of the block
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(InputRichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. Credit of the block
     */
    #[Field('credit', required: false)]
    public private(set) ?RichText $credit = null;

}
