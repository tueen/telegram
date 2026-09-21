<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with a map, corresponding to the custom HTML tag <tg-map>.
 *
 * @link https://core.telegram.org/bots/api#richblockmap
 */
class RichBlockMap extends RichBlock
{
    /**
     * Type of the block, always "map"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Location of the center of the map
     */
    #[Field('location', required: true)]
    public private(set) Location $location;

    /**
     * Map zoom level
     */
    #[Field('zoom', required: true)]
    public private(set) int $zoom;

    /**
     * Expected width of the map
     */
    #[Field('width', required: true)]
    public private(set) int $width;

    /**
     * Expected height of the map
     */
    #[Field('height', required: true)]
    public private(set) int $height;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
