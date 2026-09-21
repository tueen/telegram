<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * A quotation with centered text, loosely corresponding to the HTML tag <aside>.
 *
 * @link https://core.telegram.org/bots/api#richblockpullquotation
 */
class RichBlockPullQuotation extends RichBlock
{
    /**
     * Type of the block, always "pullquote"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

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
