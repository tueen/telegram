<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\RichText;

/**
 * A block with a "Thinking..." placeholder, corresponding to the custom HTML tag <tg-thinking>. The block may be used only in sendRichMessageDraft, therefore it can't be received in messages. See https://t.me/addemoji/AIActions for examples of custom emoji that are recommended for usage in the block.
 *
 * @link https://core.telegram.org/bots/api#richblockthinking
 */
class RichBlockThinking extends RichBlock
{
    /**
     * Type of the block, always "thinking"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Text of the block. See https://t.me/addemoji/AIActions for examples of custom emoji that are recommended for usage in the block.
     */
    #[Field('text', required: true)]
    public private(set) RichText $text;

}
