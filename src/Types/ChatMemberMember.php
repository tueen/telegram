<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ChatMemberStatus;

/**
 * Represents a chat member that has no additional privileges or restrictions.
 *
 * @link https://core.telegram.org/bots/api#chatmembermember
 */
class ChatMemberMember extends ChatMember
{
    /**
     * The member's status in the chat, always "member"
     */
    #[Field('status', required: true)]
    private(set) ChatMemberStatus|string $status;

    /**
     * Optional. Tag of the member
     */
    #[Field('tag', required: false)]
    private(set) ?string $tag = null;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    private(set) User $user;

    /**
     * Optional. Date when the user's subscription will expire; Unix time
     */
    #[Field('until_date', required: false)]
    private(set) ?int $untilDate = null;

}
