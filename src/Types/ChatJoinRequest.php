<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents a join request sent to a chat.
 *
 * @link https://core.telegram.org/bots/api#chatjoinrequest
 */
class ChatJoinRequest extends Type
{
    /**
     * Chat to which the request was sent
     */
    #[Field('chat', required: true)]
    private(set) Chat $chat;

    /**
     * User that sent the join request
     */
    #[Field('from', required: true)]
    private(set) User $from;

    /**
     * Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
     */
    #[Field('user_chat_id', required: true)]
    private(set) int $userChatId;

    /**
     * Date the request was sent in Unix time
     */
    #[Field('date', required: true)]
    private(set) int $date;

    /**
     * Optional. Bio of the user
     */
    #[Field('bio', required: false)]
    private(set) ?string $bio = null;

    /**
     * Optional. Chat invite link that was used by the user to send the join request
     */
    #[Field('invite_link', required: false)]
    private(set) ?ChatInviteLink $inviteLink = null;

    /**
     * Optional. Identifier of the join request query; for bots assigned to process join requests only. If present, then the bot must call sendChatJoinRequestWebApp or directly call answerChatJoinRequestQuery within 10 seconds.
     */
    #[Field('query_id', required: false)]
    private(set) ?string $queryId = null;

}
