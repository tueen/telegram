<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;

/**
 * Describes a service message about an ownership change in the chat.
 *
 * @link https://core.telegram.org/bots/api#chatownerchanged
 */
class ChatOwnerChanged extends Type
{
    /**
     * The new owner of the chat
     */
    #[Field('new_owner', required: true)]
    public private(set) User $newOwner;

}
