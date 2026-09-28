<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with a "Thinking..." placeholder, corresponding to the custom HTML tag <tg-thinking>. The block may be used only in sendRichMessageDraft, therefore it can't be received in messages. See https://t.me/addemoji/AIActions for examples of custom emoji that are recommended for usage in the block.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockthinking
 */
class InputRichBlockThinking extends InputRichBlock
{
    /**
     * Type of the block, always "thinking"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string|null $type = null;

    /**
     * Text of the block. See https://t.me/addemoji/AIActions for examples of custom emoji that are recommended for usage in the block.
     */
    #[Field('text', required: true)]
    private(set) ?RichText $text = null;

}
