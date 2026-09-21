<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
    public private(set) int $messageAutoDeleteTime;

}
