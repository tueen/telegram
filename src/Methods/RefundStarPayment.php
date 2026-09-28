<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Refunds a successful payment in Telegram Stars. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#refundstarpayment
 */
#[ApiMethod('refundStarPayment', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class RefundStarPayment extends Method
{
    /**
     * Identifier of the user whose payment will be refunded
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Telegram payment identifier
     */
    #[Field('telegram_payment_charge_id', required: true)]
    public string $telegramPaymentChargeId;

    public function __construct(
        int $userId,
        string $telegramPaymentChargeId,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($telegramPaymentChargeId !== null) $this->telegramPaymentChargeId = $telegramPaymentChargeId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
