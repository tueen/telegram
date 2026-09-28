<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Allows the bot to cancel or re-enable extension of a subscription paid in Telegram Stars. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#edituserstarsubscription
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('editUserStarSubscription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class EditUserStarSubscription extends Method
{
    /**
     * Identifier of the user whose subscription will be edited
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * Telegram payment identifier for the subscription
     */
    #[Field('telegram_payment_charge_id', required: true)]
    public ?string $telegramPaymentChargeId = null;

    /**
     * Pass True to cancel extension of the user subscription; the subscription must be active up to the end of the current subscription period. Pass False to allow the user to re-enable a subscription that was previously canceled by the bot.
     */
    #[Field('is_canceled', required: true)]
    public ?bool $isCanceled = null;

    public function __construct(
        ?int $userId = null,
        ?string $telegramPaymentChargeId = null,
        ?bool $isCanceled = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($telegramPaymentChargeId !== null) $this->telegramPaymentChargeId = $telegramPaymentChargeId;
        if ($isCanceled !== null) $this->isCanceled = $isCanceled;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
