<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\BackgroundTypeType;

/**
 * The background is a .PNG or .TGV (gzipped subset of SVG with MIME type "application/x-tgwallpattern") pattern to be combined with the background fill chosen by the user.
 *
 * @link https://core.telegram.org/bots/api#backgroundtypepattern
 */
class BackgroundTypePattern extends BackgroundType
{
    /**
     * Type of the background, always "pattern"
     */
    #[Field('type', required: true)]
    public private(set) BackgroundTypeType|string $type;

    /**
     * Document with the pattern
     */
    #[Field('document', required: true)]
    public private(set) Document $document;

    /**
     * The background fill that is combined with the pattern
     */
    #[Field('fill', required: true)]
    public private(set) BackgroundFill $fill;

    /**
     * Intensity of the pattern when it is shown above the filled background; 0-100
     */
    #[Field('intensity', required: true)]
    public private(set) int $intensity;

    /**
     * Optional. True, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only.
     */
    #[Field('is_inverted', required: false)]
    public private(set) ?bool $isInverted = null;

    /**
     * Optional. True, if the background moves slightly when the device is tilted
     */
    #[Field('is_moving', required: false)]
    public private(set) ?bool $isMoving = null;

}
