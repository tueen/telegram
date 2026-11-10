<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Deletes a regular gift owned by a managed business account without receiving Telegram Stars for it. Requires the can_convert_gifts_to_stars business bot right. The gift can be deleted only if it isn't limited (the field total_count of the Gift is omitted) and wasn't refunded. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletebusinessaccountgift
 */
#[ApiMethod('deleteBusinessAccountGift', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteBusinessAccountGift extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier of the regular gift that should be deleted
     */
    #[Field('owned_gift_id', required: true)]
    public ?string $ownedGiftId = null;

    public function __construct(
        ?string $businessConnectionId = null,
        ?string $ownedGiftId = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($ownedGiftId !== null) $this->ownedGiftId = $ownedGiftId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
