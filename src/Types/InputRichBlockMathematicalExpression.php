<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with a mathematical expression in LaTeX format, corresponding to the custom HTML tag <tg-math-block>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockmathematicalexpression
 */
class InputRichBlockMathematicalExpression extends InputRichBlock
{
    /**
     * Type of the block, always "mathematical_expression"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * The mathematical expression in LaTeX format
     */
    #[Field('expression', required: true)]
    public private(set) string $expression;

}
