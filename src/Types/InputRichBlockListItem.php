<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * An item of a list to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputrichblocklistitem
 */
class InputRichBlockListItem extends Type
{
    /**
     * The content of the item
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(InputRichBlock::class)]
    private(set) ?array $blocks = null;

    /**
     * Optional. Pass True if the item has a checkbox
     */
    #[Field('has_checkbox', required: false)]
    private(set) ?bool $hasCheckbox = null;

    /**
     * Optional. Pass True if the item has a checked checkbox
     */
    #[Field('is_checked', required: false)]
    private(set) ?bool $isChecked = null;

    /**
     * Optional. For ordered lists, the numeric value of the item label
     */
    #[Field('value', required: false)]
    private(set) ?int $value = null;

    /**
     * Optional. For ordered lists, the type of the item label; must be one of "a" for lowercase letters, "A" for uppercase letters, "i" for lowercase Roman numerals, "I" for uppercase Roman numerals, or "1" for decimal numbers
     */
    #[Field('type', required: false)]
    private(set) InputRichBlockType|string|null $type = null;

}
