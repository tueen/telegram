<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\MaskPositionPoint;

/**
 * This object describes the position on faces where a mask should be placed by default.
 *
 * @link https://core.telegram.org/bots/api#maskposition
 */
class MaskPosition extends Type
{
    /**
     * The part of the face relative to which the mask should be placed. One of "forehead", "eyes", "mouth", or "chin".
     */
    #[Field('point', required: true)]
    private(set) MaskPositionPoint|string $point;

    /**
     * Shift by X-axis measured in widths of the mask scaled to the face size, from left to right. For example, choosing -1.0 will place mask just to the left of the default mask position.
     */
    #[Field('x_shift', required: true)]
    private(set) float $xShift;

    /**
     * Shift by Y-axis measured in heights of the mask scaled to the face size, from top to bottom. For example, 1.0 will place the mask just below the default mask position.
     */
    #[Field('y_shift', required: true)]
    private(set) float $yShift;

    /**
     * Mask scaling coefficient. For example, 2.0 means double size.
     */
    #[Field('scale', required: true)]
    private(set) float $scale;

}
