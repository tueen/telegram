<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ChatMemberStatus;

/**
 * Represents a chat member that was banned in the chat and can't return to the chat or view chat messages.
 *
 * @link https://core.telegram.org/bots/api#chatmemberbanned
 */
class ChatMemberBanned extends ChatMember
{
    /**
     * The member's status in the chat, always "kicked"
     */
    #[Field('status', required: true)]
    private(set) ChatMemberStatus|string|null $status = null;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    private(set) ?User $user = null;

    /**
     * Date when restrictions will be lifted for this user; Unix time. If 0, then the user is banned forever.
     */
    #[Field('until_date', required: true)]
    private(set) ?int $untilDate = null;

}
