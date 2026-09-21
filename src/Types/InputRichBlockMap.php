<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputRichBlockType;
use Tueen\Telegram\Types\Location;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A block with a map, corresponding to the custom HTML tag <tg-map>. The map's width and height must not exceed 10000 in total. The width and height ratio must be at most 20.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockmap
 */
class InputRichBlockMap extends InputRichBlock
{
    /**
     * Type of the block, always "map"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * Location of the center of the map
     */
    #[Field('location', required: true)]
    public private(set) Location $location;

    /**
     * Optional. Map zoom level; 0-24
     */
    #[Field('zoom', required: false)]
    public private(set) ?int $zoom = null;

    /**
     * Optional. Map width; 0-10000
     */
    #[Field('width', required: false)]
    public private(set) ?int $width = null;

    /**
     * Optional. Map height; 0-10000
     */
    #[Field('height', required: false)]
    public private(set) ?int $height = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
