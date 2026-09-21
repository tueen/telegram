<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InputRichBlockType;
use Tueen\Telegram\Types\InputMediaPhoto;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A block with a photo, corresponding to the HTML tag <img>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockphoto
 */
class InputRichBlockPhoto extends InputRichBlock
{
    /**
     * Type of the block, always "photo"
     */
    #[Field('type', required: true)]
    public private(set) InputRichBlockType|string $type;

    /**
     * The photo. Caption is ignored.
     */
    #[Field('photo', required: true)]
    public private(set) InputMediaPhoto $photo;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
