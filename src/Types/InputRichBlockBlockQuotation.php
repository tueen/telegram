<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Content of the block
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(InputRichBlock::class)]
    private(set) ?array $blocks = null;

    /**
     * Optional. Credit of the block
     */
    #[Field('credit', required: false)]
    private(set) ?RichText $credit = null;

}
