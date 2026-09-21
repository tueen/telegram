<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Document;
use Tueen\Telegram\Types\RichBlockCaption;

/**
 * A block with a general file, corresponding to the custom HTML tag <tg-document>.
 *
 * @link https://core.telegram.org/bots/api#richblockdocument
 */
class RichBlockDocument extends RichBlock
{
    /**
     * Type of the block, always "document"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * The document
     */
    #[Field('document', required: true)]
    public private(set) Document $document;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
