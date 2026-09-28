<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Location of the center of the map
     */
    #[Field('location', required: true)]
    private(set) ?Location $location = null;

    /**
     * Optional. Map zoom level; 0-24
     */
    #[Field('zoom', required: false)]
    private(set) ?int $zoom = null;

    /**
     * Optional. Map width; 0-10000
     */
    #[Field('width', required: false)]
    private(set) ?int $width = null;

    /**
     * Optional. Map height; 0-10000
     */
    #[Field('height', required: false)]
    private(set) ?int $height = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
