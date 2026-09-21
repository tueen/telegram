<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * Cell in a table.
 *
 * @link https://core.telegram.org/bots/api#richblocktablecell
 */
class RichBlockTableCell extends Type
{
    /**
     * Optional. Text in the cell. If omitted, then the cell is invisible.
     */
    #[Field('text', required: false)]
    public private(set) ?RichText $text = null;

    /**
     * Optional. True, if the cell is a header cell
     */
    #[Field('is_header', required: false)]
    public private(set) ?bool $isHeader = null;

    /**
     * Optional. The number of columns the cell spans if it is bigger than 1
     */
    #[Field('colspan', required: false)]
    public private(set) ?int $colspan = null;

    /**
     * Optional. The number of rows the cell spans if it is bigger than 1
     */
    #[Field('rowspan', required: false)]
    public private(set) ?int $rowspan = null;

    /**
     * Horizontal cell content alignment. Currently, must be one of "left", "center", or "right".
     */
    #[Field('align', required: true)]
    public private(set) string $align;

    /**
     * Vertical cell content alignment. Currently, must be one of "top", "middle", or "bottom".
     */
    #[Field('valign', required: true)]
    public private(set) string $valign;

}
