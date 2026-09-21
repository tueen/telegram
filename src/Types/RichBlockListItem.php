<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichBlock;

/**
 * An item of a list.
 *
 * @link https://core.telegram.org/bots/api#richblocklistitem
 */
class RichBlockListItem extends Type
{
    /**
     * Label of the item
     */
    #[Field('label', required: true)]
    public private(set) string $label;

    /**
     * The content of the item
     * @var RichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(RichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. True, if the item has a checkbox
     */
    #[Field('has_checkbox', required: false)]
    public private(set) ?bool $hasCheckbox = null;

    /**
     * Optional. True, if the item has a checked checkbox
     */
    #[Field('is_checked', required: false)]
    public private(set) ?bool $isChecked = null;

    /**
     * Optional. For ordered lists, the numeric value of the item label
     */
    #[Field('value', required: false)]
    public private(set) ?int $value = null;

    /**
     * Optional. For ordered lists, the type of the item label; must be one of "a" for lowercase letters, "A" for uppercase letters, "i" for lowercase Roman numerals, "I" for uppercase Roman numerals, or "1" for decimal numbers
     */
    #[Field('type', required: false)]
    public private(set) ?string $type = null;

}
