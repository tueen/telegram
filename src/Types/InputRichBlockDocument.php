<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with a general file, corresponding to the custom HTML tag <tg-document>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockdocument
 */
class InputRichBlockDocument extends InputRichBlock
{
    /**
     * Type of the block, always "document"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string $type;

    /**
     * The document. Caption is ignored.
     */
    #[Field('document', required: true)]
    private(set) InputMediaDocument $document;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
