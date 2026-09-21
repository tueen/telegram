<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\RichTextType;

/**
 * A mathematical expression.
 *
 * @link https://core.telegram.org/bots/api#richtextmathematicalexpression
 */
class RichTextMathematicalExpression extends RichText
{
    /**
     * Type of the rich text, always "mathematical_expression"
     */
    #[Field('type', required: true)]
    public private(set) RichTextType|string $type;

    /**
     * The expression in LaTeX format
     */
    #[Field('expression', required: true)]
    public private(set) string $expression;

}
