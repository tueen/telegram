<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * An expandable block for details disclosure, corresponding to the HTML tag <details>.
 *
 * @link https://core.telegram.org/bots/api#richblockdetails
 */
class RichBlockDetails extends RichBlock
{
    /**
     * Type of the block, always "details"
     */
    #[Field('type', required: true)]
    public private(set) RichBlockType|string $type;

    /**
     * Always shown summary of the block
     */
    #[Field('summary', required: true)]
    public private(set) RichText $summary;

    /**
     * Content of the block
     * @var RichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(RichBlock::class)]
    public private(set) array $blocks;

    /**
     * Optional. True, if the content of the block is visible by default
     */
    #[Field('is_open', required: false)]
    public private(set) ?bool $isOpen = null;

}
