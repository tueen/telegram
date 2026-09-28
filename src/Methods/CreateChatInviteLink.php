<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\ChatInviteLink;

/**
 * Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
 *
 * @link https://core.telegram.org/bots/api#createchatinvitelink
 */
#[ApiMethod('createChatInviteLink', 'POST')]
#[ReturnType(ChatInviteLink::class, isArray: false)]
class CreateChatInviteLink extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

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
        ?string $name = null,
        ?int $expireDate = null,
        ?int $memberLimit = null,
        ?bool $createsJoinRequest = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($name !== null) $this->name = $name;
        if ($expireDate !== null) $this->expireDate = $expireDate;
        if ($memberLimit !== null) $this->memberLimit = $memberLimit;
        if ($createsJoinRequest !== null) $this->createsJoinRequest = $createsJoinRequest;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
