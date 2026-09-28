<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A list of blocks, corresponding to the HTML tag <ul> or <ol> with multiple nested tags <li>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblocklist
 */
class InputRichBlockList extends InputRichBlock
{
    /**
     * Type of the block, always "list"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string $type;

    /**
     * Items of the list
     * @var InputRichBlockListItem[]|null
     */
    #[Field('items', required: true)]
    #[ArrayOf(InputRichBlockListItem::class)]
    private(set) array $items;

}
