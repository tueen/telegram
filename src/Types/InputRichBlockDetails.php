<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;
use Tueen\Telegram\Types\InputRichBlock;

/**
 * An expandable block for details disclosure, corresponding to the HTML tag <details>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockdetails
 */
class InputRichBlockDetails extends InputRichBlock
{
    /**
     * Type of the block, always "details"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Always shown summary of the block
     */
    #[Field('summary', required: true)]
    public private(set) RichText $summary;

    /**
     * Content of the block
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(InputRichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. Pass True if the content of the block is visible by default
     */
    #[Field('is_open', required: false)]
    public private(set) ?bool $isOpen = null;

}
