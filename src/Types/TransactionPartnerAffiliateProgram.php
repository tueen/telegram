<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes the affiliate program that issued the affiliate commission received via this transaction.
 *
 * @link https://core.telegram.org/bots/api#transactionpartneraffiliateprogram
 */
class TransactionPartnerAffiliateProgram extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "affiliate_program"
     */
    #[Field('type', required: true)]
    private(set) TransactionPartnerType|string $type;

    /**
     * Optional. Information about the bot that sponsored the affiliate program
     */
    #[Field('sponsor_user', required: false)]
    private(set) ?User $sponsorUser = null;

    /**
     * The number of Telegram Stars received by the bot for each 1000 Telegram Stars received by the affiliate program sponsor from referred users
     */
    #[Field('commission_per_mille', required: true)]
    private(set) int $commissionPerMille;

}
