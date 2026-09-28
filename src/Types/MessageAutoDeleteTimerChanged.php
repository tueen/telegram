<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a service message about a change in auto-delete timer settings.
 *
 * @link https://core.telegram.org/bots/api#messageautodeletetimerchanged
 */
class MessageAutoDeleteTimerChanged extends Type
{
    /**
     * New auto-delete time for messages in the chat; in seconds
     */
    #[Field('message_auto_delete_time', required: true)]
    private(set) int $messageAutoDeleteTime;

}
