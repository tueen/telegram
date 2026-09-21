<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;

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
    public private(set) string $type;

    /**
     * Optional. Information about the bot that sponsored the affiliate program
     */
    #[Field('sponsor_user', required: false)]
    public private(set) ?User $sponsorUser = null;

    /**
     * The number of Telegram Stars received by the bot for each 1000 Telegram Stars received by the affiliate program sponsor from referred users
     */
    #[Field('commission_per_mille', required: true)]
    public private(set) int $commissionPerMille;

}
