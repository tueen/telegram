<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\StarAmount;

/**
 * Returns the amount of Telegram Stars owned by a managed business account. Requires the can_view_gifts_and_stars business bot right. Returns StarAmount on success.
 *
 * @link https://core.telegram.org/bots/api#getbusinessaccountstarbalance
 */
#[ApiMethod('getBusinessAccountStarBalance', 'POST')]
#[ReturnType(StarAmount::class, isArray: false)]
class GetBusinessAccountStarBalance extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    public function __construct(
        string $businessConnectionId
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
    }

    public static function make(
        string $businessConnectionId
    ): static
    {
        return new static($businessConnectionId);
    }
}
