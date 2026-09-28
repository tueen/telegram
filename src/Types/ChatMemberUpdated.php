<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents changes in the status of a chat member.
 *
 * @link https://core.telegram.org/bots/api#chatmemberupdated
 */
class ChatMemberUpdated extends Type
{
    /**
     * Chat the user belongs to
     */
    #[Field('chat', required: true)]
    private(set) ?Chat $chat = null;

    /**
     * Performer of the action, which resulted in the change
     */
    #[Field('from', required: true)]
    private(set) ?User $from = null;

    /**
     * Date the change was done in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * Previous information about the chat member
     */
    #[Field('old_chat_member', required: true)]
    private(set) ?ChatMember $oldChatMember = null;

    /**
     * New information about the chat member
     */
    #[Field('new_chat_member', required: true)]
    private(set) ?ChatMember $newChatMember = null;

    /**
     * Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events only
     */
    #[Field('invite_link', required: false)]
    private(set) ?ChatInviteLink $inviteLink = null;

    /**
     * Optional. True, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
     */
    #[Field('via_join_request', required: false)]
    private(set) ?bool $viaJoinRequest = null;

    /**
     * Optional. True, if the user joined the chat via a chat folder invite link
     */
    #[Field('via_chat_folder_invite_link', required: false)]
    private(set) ?bool $viaChatFolderInviteLink = null;

}
