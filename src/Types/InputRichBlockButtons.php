<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <tg-button-row>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockbuttons
 */
class InputRichBlockButtons extends InputRichBlock
{
    /**
     * Type of the block, always "buttons"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * List of 1-8 buttons to send
     * @var RichMessageButton[]|null
     */
    #[Field('buttons', required: true)]
    #[ArrayOf(RichMessageButton::class)]
    public private(set) array $buttons;

    /**
     * Optional. Horizontal alignment of the buttons. Currently, must be one of "left", "center", or "right".
     */
    #[Field('align', required: false)]
    public private(set) ?string $align = null;

}
