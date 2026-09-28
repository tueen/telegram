<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <tg-button-row>.
 *
 * @link https://core.telegram.org/bots/api#richblockbuttons
 */
class RichBlockButtons extends RichBlock
{
    /**
     * Type of the block, always "buttons"
     */
    #[Field('type', required: true)]
    private(set) RichBlockType|string $type;

    /**
     * The buttons
     * @var RichMessageButton[]|null
     */
    #[Field('buttons', required: true)]
    #[ArrayOf(RichMessageButton::class)]
    private(set) array $buttons;

    /**
     * Optional. Horizontal alignment of the buttons. Currently, must be one of "left", "center", or "right".
     */
    #[Field('align', required: false)]
    private(set) ?string $align = null;

}
