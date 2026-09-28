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
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Changes the username of a managed business account. Requires the can_change_username business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountusername
 *
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setBusinessAccountUsername', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class SetBusinessAccountUsername extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    /**
     * The new value of the username for the business account; 0-32 characters
     */
    #[Field('username', required: false)]
    public ?string $username = null;

    public function __construct(
        ?string $businessConnectionId = null,
        ?string $username = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($username !== null) $this->username = $username;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
