<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * Relative size of the text font; 1-6, 1 is the largest, 6 is the smallest
     */
    #[Field('size', required: true)]
    private(set) ?int $size = null;

}
