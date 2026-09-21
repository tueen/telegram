<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

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
    public private(set) string $type;

    /**
     * Content of the block
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * Optional. Credit of the block
     */
    #[Field('credit', required: false)]
    public private(set) ?RichText $credit = null;

}
