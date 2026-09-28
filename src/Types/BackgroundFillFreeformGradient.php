<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundFillType;

/**
 * The background is a freeform gradient that rotates after every message in the chat.
 *
 * @link https://core.telegram.org/bots/api#backgroundfillfreeformgradient
 */
class BackgroundFillFreeformGradient extends BackgroundFill
{
    /**
     * Type of the background fill, always "freeform_gradient"
     */
    #[Field('type', required: true)]
    private(set) BackgroundFillType|string|null $type = null;

    /**
     * A list of the 3 or 4 base colors that are used to generate the freeform gradient in the RGB24 format
     * @var Integer[]|null
     */
    #[Field('colors', required: true)]
    private(set) ?array $colors = null;

}
