<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;

/**
 * Represents a chat member that isn't currently a member of the chat, but may join it themselves.
 *
 * @link https://core.telegram.org/bots/api#chatmemberleft
 */
class ChatMemberLeft extends ChatMember
{
    /**
     * The member's status in the chat, always "left"
     */
    #[Field('status', required: true)]
    public private(set) string $status;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    public private(set) User $user;

}
