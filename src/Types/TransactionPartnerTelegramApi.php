<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

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
    public private(set) string $type;

    /**
     * The number of successful requests that exceeded regular limits and were therefore billed
     */
    #[Field('request_count', required: true)]
    public private(set) int $requestCount;

}
