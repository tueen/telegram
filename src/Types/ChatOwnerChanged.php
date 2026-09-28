<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

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
    private(set) ?User $newOwner = null;

}
