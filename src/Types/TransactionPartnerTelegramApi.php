<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\TransactionPartnerType;

/**
 * Describes a transaction with payment for paid broadcasting.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnertelegramapi
 */
class TransactionPartnerTelegramApi extends TransactionPartner
{
    /**
     * Type of the transaction partner, always "telegram_api"
     */
    #[Field('type', required: true)]
    private(set) TransactionPartnerType|string|null $type = null;

    /**
     * The number of successful requests that exceeded regular limits and were therefore billed
     */
    #[Field('request_count', required: true)]
    private(set) ?int $requestCount = null;

}
