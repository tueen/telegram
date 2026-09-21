<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * Caption of a rich formatted block.
 *
 * @link https://core.telegram.org/bots/api#richblockcaption
 */
class RichBlockCaption extends Type
{
    /**
     * Block caption
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

    /**
     * Optional. Block credit which corresponds to the HTML tag <cite>
     */
    #[Field('credit', required: false)]
    public private(set) ?RichText $credit = null;

}
