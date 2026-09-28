<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
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
    private(set) RichTextType|string $type;

    /**
     * The expression in LaTeX format
     */
    #[Field('expression', required: true)]
    private(set) string $expression;

}
