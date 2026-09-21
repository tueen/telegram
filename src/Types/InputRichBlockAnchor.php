<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with an anchor, corresponding to the HTML tag <a> with the attribute name.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockanchor
 */
class InputRichBlockAnchor extends InputRichBlock
{
    /**
     * Type of the block, always "anchor"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * The name of the anchor
     */
    #[Field('name', required: true)]
    public private(set) string $name;

}
