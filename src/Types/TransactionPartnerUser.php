<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes a transaction with a user.
 *
 * @link https://core.telegram.org/bots/api#transactionpartneruser
 */
class TransactionPartnerUser extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "user"
     */
    #[Field('type', required: true)]
    private(set) TransactionPartnerType|string $type;

    /**
     * Type of the transaction, currently one of "invoice_payment" for payments via invoices, "paid_media_payment" for payments for paid media, "gift_purchase" for gifts sent by the bot, "premium_purchase" for Telegram Premium subscriptions gifted by the bot, "business_account_transfer" for direct transfers from managed business accounts
     */
    #[Field('transaction_type', required: true)]
    private(set) string $transactionType;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    private(set) User $user;

    /**
     * Optional. Information about the affiliate that received a commission via this transaction. Can be available only for "invoice_payment" and "paid_media_payment" transactions.
     */
    #[Field('affiliate', required: false)]
    private(set) ?AffiliateInfo $affiliate = null;

    /**
     * Optional. Bot-specified invoice payload. Can be available only for "invoice_payment" transactions.
     */
    #[Field('invoice_payload', required: false)]
    private(set) ?string $invoicePayload = null;

    /**
     * Optional. The duration of the paid subscription. Can be available only for "invoice_payment" transactions.
     */
    #[Field('subscription_period', required: false)]
    private(set) ?int $subscriptionPeriod = null;

    /**
     * Optional. Information about the paid media bought by the user; for "paid_media_payment" transactions only
     * @var PaidMedia[]|null
     */
    #[Field('paid_media', required: false)]
    #[ArrayOf(PaidMedia::class)]
    private(set) ?array $paidMedia = null;

    /**
     * Optional. Bot-specified paid media payload. Can be available only for "paid_media_payment" transactions.
     */
    #[Field('paid_media_payload', required: false)]
    private(set) ?string $paidMediaPayload = null;

    /**
     * Optional. The gift sent to the user by the bot; for "gift_purchase" transactions only
     */
    #[Field('gift', required: false)]
    private(set) ?Gift $gift = null;

    /**
     * Optional. Number of months the gifted Telegram Premium subscription will be active for; for "premium_purchase" transactions only
     */
    #[Field('premium_subscription_duration', required: false)]
    private(set) ?int $premiumSubscriptionDuration = null;

}
