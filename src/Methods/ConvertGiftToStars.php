<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Converts a given regular gift to Telegram Stars. Requires the can_convert_gifts_to_stars business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#convertgifttostars
 *
 * @throws NotEnoughRightsException
 * @throws ApiException
 */
#[ApiMethod('convertGiftToStars', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::NotEnoughRights])]
class ConvertGiftToStars extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier of the regular gift that should be converted to Telegram Stars
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
