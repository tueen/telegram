<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\ChatInviteLink;

/**
 * Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a ChatInviteLink object.
 *
 * @link https://core.telegram.org/bots/api#editchatinvitelink
 */
#[ApiMethod('editChatInviteLink', 'POST')]
#[ReturnType(ChatInviteLink::class, isArray: false)]
class EditChatInviteLink extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * The invite link to edit
     */
    #[Field('invite_link', required: true)]
    public string $inviteLink;

    /**
     * Invite link name; 0-32 characters
     */
    #[Field('name', required: false)]
    public ?string $name = null;

    /**
     * Point in time (Unix timestamp) when the link will expire
     */
    #[Field('expire_date', required: false)]
    public ?int $expireDate = null;

    /**
     * The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
     */
    #[Field('member_limit', required: false)]
    public ?int $memberLimit = null;

    /**
     * True, if users joining the chat via the link need to be approved by chat administrators. If True, member_limit can't be specified.
     */
    #[Field('creates_join_request', required: false)]
    public ?bool $createsJoinRequest = null;

    public function __construct(
        int|string $chatId,
        string $inviteLink,
        ?string $name = null,
        ?int $expireDate = null,
        ?int $memberLimit = null,
        ?bool $createsJoinRequest = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($inviteLink !== null) $this->inviteLink = $inviteLink;
        if ($name !== null) $this->name = $name;
        if ($expireDate !== null) $this->expireDate = $expireDate;
        if ($memberLimit !== null) $this->memberLimit = $memberLimit;
        if ($createsJoinRequest !== null) $this->createsJoinRequest = $createsJoinRequest;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
