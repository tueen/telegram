<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Voice;
use Tueen\Telegram\Types\RichBlockCaption;

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
    public private(set) string $type;

    /**
     * The voice note
     */
    #[Field('voice_note', required: true)]
    public private(set) Voice $voiceNote;

    /**
     * Optional. Caption of the block
     */
    #[Field('caption', required: false)]
    public private(set) ?RichBlockCaption $caption = null;

}
