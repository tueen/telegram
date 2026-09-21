<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * A preformatted text block, corresponding to the nested HTML tags <pre> and <code>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockpreformatted
 */
class InputRichBlockPreformatted extends InputRichBlock
{
    /**
     * Type of the block, always "pre"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * Optional. The programming language of the text
     */
    #[Field('language', required: false)]
    public private(set) ?string $language = null;

}
