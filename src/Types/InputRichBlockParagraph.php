<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A text paragraph, corresponding to the HTML tag <p>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockparagraph
 */
class InputRichBlockParagraph extends InputRichBlock
{
    /**
     * Type of the block, always "paragraph"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

}
