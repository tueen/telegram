<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes an inline message sent by a guest bot.
 *
 * @link https://core.telegram.org/bots/api#sentguestmessage
 */
class SentGuestMessage extends Type
{
    /**
     * Identifier of the sent inline message
     */
    #[Field('inline_message_id', required: true)]
    private(set) ?string $inlineMessageId = null;

}
