<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A footer, corresponding to the HTML tag <footer>.
 *
 * @link https://core.telegram.org/bots/api#richblockfooter
 */
class RichBlockFooter extends RichBlock
{
    /**
     * Type of the block, always "footer"
     */
    #[Field('type', required: true)]
    private(set) RichBlockType|string|null $type = null;

    /**
     * Text of the block
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

}
