<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with a photo, corresponding to the HTML tag <img>.
 *
 * @link https://core.telegram.org/bots/api#richblockphoto
 */
class RichBlockPhoto extends RichBlock
{
    /**
     * Type of the block, always "photo"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Available sizes of the photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: true)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) array $photo;

    /**
     * Optional. True, if the media preview is covered by a spoiler animation
     */
    #[Field('has_spoiler', required: false)]
    public private(set) ?bool $hasSpoiler = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
