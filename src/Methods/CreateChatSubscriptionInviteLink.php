<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\ChatInviteLink;

/**
 * Use this method to create a subscription invite link for a channel chat. The bot must have the can_invite_users administrator rights. The link can be edited using the method editChatSubscriptionInviteLink or revoked using the method revokeChatInviteLink. Returns the new invite link as a ChatInviteLink object.
 *
 * @link https://core.telegram.org/bots/api#createchatsubscriptioninvitelink
 *
 * @throws ChatNotFoundException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('createChatSubscriptionInviteLink', 'POST')]
#[ReturnType(ChatInviteLink::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class CreateChatSubscriptionInviteLink extends Method
{
    /**
     * Unique identifier for the target channel chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * The number of seconds the subscription will be active for before the next payment. Currently, it must always be 2592000 (30 days).
     */
    #[Field('subscription_period', required: true)]
    public ?int $subscriptionPeriod = null;

    /**
     * The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat; 1-10000
     */
    #[Field('subscription_price', required: true)]
    public ?int $subscriptionPrice = null;

    /**
     * Invite link name; 0-32 characters
     */
    #[Field('name', required: false)]
    public ?string $name = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $subscriptionPeriod = null,
        ?int $subscriptionPrice = null,
        ?string $name = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($subscriptionPeriod !== null) $this->subscriptionPeriod = $subscriptionPeriod;
        if ($subscriptionPrice !== null) $this->subscriptionPrice = $subscriptionPrice;
        if ($name !== null) $this->name = $name;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
