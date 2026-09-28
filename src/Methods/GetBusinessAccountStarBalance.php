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
use Tueen\Telegram\Types\StarAmount;

/**
 * Returns the amount of Telegram Stars owned by a managed business account. Requires the can_view_gifts_and_stars business bot right. Returns StarAmount on success.
 *
 * @link https://core.telegram.org/bots/api#getbusinessaccountstarbalance
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getBusinessAccountStarBalance', 'POST')]
#[ReturnType(StarAmount::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class GetBusinessAccountStarBalance extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    public function __construct(
        ?string $businessConnectionId = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
