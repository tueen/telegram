<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ChatMemberStatus;

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
    private(set) ChatMemberStatus|string $status;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    private(set) User $user;

}
