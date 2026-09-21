<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the background of a gift.
 *
 * @link https://core.telegram.org/bots/api#giftbackground
 */
class GiftBackground extends Type
{
    /**
     * Center color of the background in RGB format
     */
    #[Field('center_color', required: true)]
    public private(set) int $centerColor;

    /**
     * Edge color of the background in RGB format
     */
    #[Field('edge_color', required: true)]
    public private(set) int $edgeColor;

    /**
     * Text color of the background in RGB format
     */
    #[Field('text_color', required: true)]
    public private(set) int $textColor;

}
