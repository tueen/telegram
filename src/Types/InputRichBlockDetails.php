<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

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
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Always shown summary of the block
     */
    #[Field('summary', required: true)]
    private(set) ?RichText $summary = null;

    /**
     * Content of the block
     * @var InputRichBlock[]|null
     */
    #[Field('blocks', required: true)]
    #[ArrayOf(InputRichBlock::class)]
    private(set) ?array $blocks = null;

    /**
     * Optional. Pass True if the content of the block is visible by default
     */
    #[Field('is_open', required: false)]
    private(set) ?bool $isOpen = null;

}
