<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with an anchor, corresponding to the HTML tag <a> with the attribute name.
 *
 * @link https://core.telegram.org/bots/api#richblockanchor
 */
class RichBlockAnchor extends RichBlock
{
    /**
     * Type of the block, always "anchor"
     */
    #[Field('type', required: true)]
    private(set) RichBlockType|string $type;

    /**
     * The name of the anchor
     */
    #[Field('name', required: true)]
    private(set) string $name;

}
