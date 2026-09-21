<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\InputMediaVoiceNote;
use Tueen\Telegram\Types\RichBlockCaption;

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
    public private(set) string $type;

    /**
     * The voice note. Caption is ignored.
     */
    #[Field('voice_note', required: true)]
    public private(set) InputMediaVoiceNote $voiceNote;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
