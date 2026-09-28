<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * The photo. Caption is ignored.
     */
    #[Field('photo', required: true)]
    private(set) ?InputMediaPhoto $photo = null;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
