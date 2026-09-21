<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputRichBlockType;
use Tueen\Telegram\Types\RichText;

/**
 * A section heading, corresponding to the HTML tags <h1>, <h2>, <h3>, <h4>, <h5>, or <h6>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblocksectionheading
 */
class InputRichBlockSectionHeading extends InputRichBlock
{
    /**
     * Type of the block, always "heading"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * Relative size of the text font; 1-6, 1 is the largest, 6 is the smallest
     */
    #[Field('size', required: true)]
    public private(set) int $size;

}
