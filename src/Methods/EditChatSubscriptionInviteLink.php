<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\ChatInviteLink;

/**
 * Use this method to edit a subscription invite link created by the bot. The bot must have the can_invite_users administrator rights. Returns the edited invite link as a ChatInviteLink object.
 *
 * @link https://core.telegram.org/bots/api#editchatsubscriptioninvitelink
 */
#[ApiMethod('editChatSubscriptionInviteLink', 'POST')]
#[ReturnType(ChatInviteLink::class, isArray: false)]
class EditChatSubscriptionInviteLink extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * The invite link to edit
     */
    #[Field('invite_link', required: true)]
    public ?string $inviteLink = null;

    /**
     * Invite link name; 0-32 characters
     */
    #[Field('name', required: false)]
    public ?string $name = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $inviteLink = null,
        ?string $name = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($inviteLink !== null) $this->inviteLink = $inviteLink;
        if ($name !== null) $this->name = $name;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
