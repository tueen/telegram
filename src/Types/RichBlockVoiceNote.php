<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\RichBlockType;

/**
 * A block with a voice note, corresponding to the HTML tag <audio>.
 *
 * @link https://core.telegram.org/bots/api#richblockvoicenote
 */
class RichBlockVoiceNote extends RichBlock
{
    /**
     * Type of the block, always "voice_note"
     */
    #[Field('type', required: true)]
    private(set) RichBlockType|string $type;

    /**
     * The voice note
     */
    #[Field('voice_note', required: true)]
    private(set) Voice $voiceNote;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    private(set) ?RichBlockCaption $caption = null;

}
