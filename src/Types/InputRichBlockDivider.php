<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * A divider, corresponding to the HTML tag <hr/>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockdivider
 */
class InputRichBlockDivider extends InputRichBlock
{
    /**
     * Type of the block, always "divider"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

}
