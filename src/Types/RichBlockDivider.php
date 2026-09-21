<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A divider, corresponding to the HTML tag <hr/>.
 *
 * @link https://core.telegram.org/bots/api#richblockdivider
 */
class RichBlockDivider extends RichBlock
{
    /**
     * Type of the block, always "divider"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

}
