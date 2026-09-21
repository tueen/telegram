<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about the chat owner leaving the chat.
 *
 * @link https://core.telegram.org/bots/api#chatownerleft
 */
class ChatOwnerLeft extends Type
{
    /**
     * Optional. The user who will become the new owner of the chat if the previous owner does not return to the chat
     */
    #[Field('new_owner', required: false)]
    public private(set) ?User $newOwner = null;

}
