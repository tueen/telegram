<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Converts a given regular gift to Telegram Stars. Requires the can_convert_gifts_to_stars business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#convertgifttostars
 */
#[ApiMethod('convertGiftToStars', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class ConvertGiftToStars extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the regular gift that should be converted to Telegram Stars
     */
    #[Field('owned_gift_id', required: true)]
    public string $ownedGiftId;

    public function __construct(
        string $businessConnectionId,
        string $ownedGiftId
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($ownedGiftId !== null) $this->ownedGiftId = $ownedGiftId;
    }

    public static function make(
        string $businessConnectionId,
        string $ownedGiftId
    ): static
    {
        return new static($businessConnectionId, $ownedGiftId);
    }
}
