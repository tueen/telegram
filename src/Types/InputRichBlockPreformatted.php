<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

    /**
     * Optional. The programming language of the text
     */
    #[Field('language', required: false)]
    private(set) ?string $language = null;

}
