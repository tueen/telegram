<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Allows the bot to cancel or re-enable extension of a subscription paid in Telegram Stars. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#edituserstarsubscription
 */
#[ApiMethod('editUserStarSubscription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class EditUserStarSubscription extends Method
{
    /**
     * Identifier of the user whose subscription will be edited
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Telegram payment identifier for the subscription
     */
    #[Field('telegram_payment_charge_id', required: true)]
    public string $telegramPaymentChargeId;

    /**
     * Pass True to cancel extension of the user subscription; the subscription must be active up to the end of the current subscription period. Pass False to allow the user to re-enable a subscription that was previously canceled by the bot.
     */
    #[Field('is_canceled', required: true)]
    public bool $isCanceled;

    public function __construct(
        int $userId,
        string $telegramPaymentChargeId,
        bool $isCanceled,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($telegramPaymentChargeId !== null) $this->telegramPaymentChargeId = $telegramPaymentChargeId;
        if ($isCanceled !== null) $this->isCanceled = $isCanceled;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
