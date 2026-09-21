<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes a withdrawal transaction to the Telegram Ads platform.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnertelegramads
 */
class TransactionPartnerTelegramAds extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "telegram_ads"
     */
    #[Field('type', required: true)]
    public private(set) TransactionPartnerType|string $type;

}
