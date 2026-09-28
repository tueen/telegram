<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A footer, corresponding to the HTML tag <footer>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockfooter
 */
class InputRichBlockFooter extends InputRichBlock
{
    /**
     * Type of the block, always "footer"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string $type;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    private(set) RichText $text;

}
