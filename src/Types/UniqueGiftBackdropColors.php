<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the colors of the backdrop of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftbackdropcolors
 */
class UniqueGiftBackdropColors extends Type
{
    /**
     * The color in the center of the backdrop in RGB format
     */
    #[Field('center_color', required: true)]
    public private(set) int $centerColor;

    /**
     * The color on the edges of the backdrop in RGB format
     */
    #[Field('edge_color', required: true)]
    public private(set) int $edgeColor;

    /**
     * The color to be applied to the symbol in RGB format
     */
    #[Field('symbol_color', required: true)]
    public private(set) int $symbolColor;

    /**
     * The color for the text on the backdrop in RGB format
     */
    #[Field('text_color', required: true)]
    public private(set) int $textColor;

}
