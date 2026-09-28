<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\UserNotFoundException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Refunds a successful payment in Telegram Stars. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#refundstarpayment
 *
 * @throws UserNotFoundException
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('refundStarPayment', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::UserNotFound, TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class RefundStarPayment extends Method
{
    /**
     * Identifier of the user whose payment will be refunded
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * Telegram payment identifier
     */
    #[Field('telegram_payment_charge_id', required: true)]
    public ?string $telegramPaymentChargeId = null;

    public function __construct(
        ?int $userId = null,
        ?string $telegramPaymentChargeId = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($telegramPaymentChargeId !== null) $this->telegramPaymentChargeId = $telegramPaymentChargeId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
