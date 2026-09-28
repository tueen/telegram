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
use Tueen\Telegram\Exceptions\UserNotFoundException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Removes verification from a user who is currently verified on behalf of the organization represented by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#removeuserverification
 *
 * @throws UserNotFoundException
 * @throws NotEnoughRightsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('removeUserVerification', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::UserNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::FloodWait])]
class RemoveUserVerification extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    public function __construct(
        ?int $userId = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
