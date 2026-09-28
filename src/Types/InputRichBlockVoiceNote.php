<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InputRichBlockType;

/**
 * A block with a voice note, corresponding to the HTML tag <audio>.
 *
 * @link https://core.telegram.org/bots/api#inputrichblockvoicenote
 */
class InputRichBlockVoiceNote extends InputRichBlock
{
    /**
     * Type of the block, always "voice_note"
     */
    #[Field('type', required: true)]
    private(set) InputRichBlockType|string $type;

    /**
     * The voice note. Caption is ignored.
     */
    #[Field('voice_note', required: true)]
    private(set) InputMediaVoiceNote $voiceNote;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
