<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\ChatMemberStatus;
use Tueen\Telegram\Types\User;

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
    public private(set) ChatMemberStatus|string $status;

    /**
     * Optional. Tag of the member
     */
    #[Field('tag', required: false)]
    public private(set) ?string $tag = null;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    public private(set) User $user;

    /**
     * Optional. Date when the user's subscription will expire; Unix time
     */
    #[Field('until_date', required: false)]
    public private(set) ?int $untilDate = null;

}
