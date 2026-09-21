<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\ChatMemberStatus;
use Tueen\Telegram\Types\User;

/**
 * Represents a chat member that owns the chat and has all administrator privileges.
 *
 * @link https://core.telegram.org/bots/api#chatmemberowner
 */
class ChatMemberOwner extends ChatMember
{
    /**
     * The member's status in the chat, always "creator"
     */
    #[Field('status', required: true)]
    public private(set) ChatMemberStatus|string $status;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    public private(set) User $user;

    /**
     * True, if the user's presence in the chat is hidden
     */
    #[Field('is_anonymous', required: true)]
    public private(set) bool $isAnonymous;

    /**
     * Optional. Custom title for this user
     */
    #[Field('custom_title', required: false)]
    public private(set) ?string $customTitle = null;

}
