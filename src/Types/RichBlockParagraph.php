<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A text paragraph, corresponding to the HTML tag <p>.
 *
 * @link https://core.telegram.org/bots/api#richblockparagraph
 */
class RichBlockParagraph extends RichBlock
{
    /**
     * Type of the block, always "paragraph"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

}
