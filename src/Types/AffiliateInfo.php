<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Contains information about the affiliate that received a commission via this transaction.
 *
 * @link https://core.telegram.org/bots/api#affiliateinfo
 */
class AffiliateInfo extends Type
{
    /**
     * Optional. The bot or the user that received an affiliate commission if it was received by a bot or a user
     */
    #[Field('affiliate_user', required: false)]
    private(set) ?User $affiliateUser = null;

    /**
     * Optional. The chat that received an affiliate commission if it was received by a chat
     */
    #[Field('affiliate_chat', required: false)]
    private(set) ?Chat $affiliateChat = null;

    /**
     * The number of Telegram Stars received by the affiliate for each 1000 Telegram Stars received by the bot from referred users
     */
    #[Field('commission_per_mille', required: true)]
    private(set) ?int $commissionPerMille = null;

    /**
     * Integer amount of Telegram Stars received by the affiliate from the transaction, rounded to 0; can be negative for refunds
     */
    #[Field('amount', required: true)]
    private(set) ?int $amount = null;

    /**
     * Optional. The number of 1/1000000000 shares of Telegram Stars received by the affiliate; from -999999999 to 999999999; can be negative for refunds
     */
    #[Field('nanostar_amount', required: false)]
    private(set) ?int $nanostarAmount = null;

}
