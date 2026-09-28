<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A table, corresponding to the HTML tag <table>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblocktable
 */
class InputRichBlockTable extends InputRichBlock
{
    /**
     * Type of the block, always "table"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Cells of the table
     * @var RichBlockTableCell[]|null
     */
    #[Field('cells', required: true)]
    #[ArrayOf(RichBlockTableCell::class)]
    private(set) ?array $cells = null;

    /**
     * Optional. Pass True if the table has borders
     */
    #[Field('is_bordered', required: false)]
    private(set) ?bool $isBordered = null;

    /**
     * Optional. Pass True if the table is striped
     */
    #[Field('is_striped', required: false)]
    private(set) ?bool $isStriped = null;

    /**
     * Optional. Pass True if table cells must have smaller indents
     */
    #[Field('is_compact', required: false)]
    private(set) ?bool $isCompact = null;

    /**
     * Optional. Caption of the table
     */
    #[Field('caption', required: false)]
    private(set) ?RichText $caption = null;

}
