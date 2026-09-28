<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a unique message identifier.
 *
 * @link https://core.telegram.org/bots/api#messageid
 */
class MessageId extends Type
{
    /**
     * Unique message identifier. In specific instances (e.g., message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent.
     */
    #[Field('message_id', required: true)]
    private(set) int $messageId;

}
