<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\ChatInviteLink;

/**
 * Use this method to create a subscription invite link for a channel chat. The bot must have the can_invite_users administrator rights. The link can be edited using the method editChatSubscriptionInviteLink or revoked using the method revokeChatInviteLink. Returns the new invite link as a ChatInviteLink object.
 *
 * @link https://core.telegram.org/bots/api#createchatsubscriptioninvitelink
 */
#[ApiMethod('createChatSubscriptionInviteLink', 'POST')]
#[ReturnType(ChatInviteLink::class, isArray: false)]
class CreateChatSubscriptionInviteLink extends Method
{
    /**
     * Unique identifier for the target channel chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * The number of seconds the subscription will be active for before the next payment. Currently, it must always be 2592000 (30 days).
     */
    #[Field('subscription_period', required: true)]
    public int $subscriptionPeriod;

    /**
     * The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat; 1-10000
     */
    #[Field('subscription_price', required: true)]
    public int $subscriptionPrice;

    /**
     * Invite link name; 0-32 characters
     */
    #[Field('name', required: false)]
    public ?string $name = null;

    public function __construct(
        int|string $chatId,
        int $subscriptionPeriod,
        int $subscriptionPrice,
        ?string $name = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($subscriptionPeriod !== null) $this->subscriptionPeriod = $subscriptionPeriod;
        if ($subscriptionPrice !== null) $this->subscriptionPrice = $subscriptionPrice;
        if ($name !== null) $this->name = $name;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
