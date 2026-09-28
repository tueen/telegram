<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block quotation, corresponding to the HTML tag <blockquote> with custom attribute "expandable".
 *
 * @link https://core.telegram.org/bots/api#inputrichblockexpandableblockquotation
 */
class InputRichBlockExpandableBlockQuotation extends InputRichBlock
{
    /**
     * Type of the block, always "expandable_blockquote"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Content of the block
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * Optional. Credit of the block
     */
    #[Field('credit', required: false)]
    private(set) ?RichText $credit = null;

}
