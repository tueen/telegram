<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with a mathematical expression in LaTeX format, corresponding to the custom HTML tag <tg-math-block>.
 *
 * @link https://core.telegram.org/bots/api#richblockmathematicalexpression
 */
class RichBlockMathematicalExpression extends RichBlock
{
    /**
     * Type of the block, always "mathematical_expression"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * The mathematical expression in LaTeX format
     */
    #[Field('expression', required: true)]
    public private(set) string $expression;

}
