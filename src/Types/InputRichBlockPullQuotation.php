<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A quotation with centered text, loosely corresponding to the HTML tag <aside>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockpullquotation
 */
class InputRichBlockPullQuotation extends InputRichBlock
{
    /**
     * Type of the block, always "pullquote"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * Optional. Credit of the block
     */
    #[Field('credit', required: false)]
    public private(set) ?RichText $credit = null;

}
