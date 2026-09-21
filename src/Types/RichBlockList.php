<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichBlockType;
use Tueen\Telegram\Types\RichBlockListItem;

/**
 * A list of blocks, corresponding to the HTML tag <ul> or <ol> with multiple nested tags <li>.
 *
 * @link https://core.telegram.org/bots/api#richblocklist
 */
class RichBlockList extends RichBlock
{
    /**
     * Type of the block, always "list"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Items of the list
     * @var RichBlockListItem[]|null
     */
    #[Field('items', required: true)]
    #[ArrayOf(RichBlockListItem::class)]
    public private(set) array $items;

}
