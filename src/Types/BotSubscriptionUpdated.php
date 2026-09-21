<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about changes to a user payment subscription toward the current bot.
 *
 * @link https://core.telegram.org/bots/api#botsubscriptionupdated
 */
class BotSubscriptionUpdated extends Type
{
    /**
     * User who subscribed for payments toward the bot
     */
    #[Field('user', required: true)]
    public private(set) User $user;

    /**
     * Bot-specified invoice payload
     */
    #[Field('invoice_payload', required: true)]
    public private(set) string $invoicePayload;

    /**
     * The new state of the subscription. Currently, it can be one of "canceled" if the user canceled the subscription, "active" if the user re-enabled a previously canceled subscription, or "failed" if payment for the subscription failed.
     */
    #[Field('state', required: true)]
    public private(set) string $state;

}
