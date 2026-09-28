<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string $type;

}
