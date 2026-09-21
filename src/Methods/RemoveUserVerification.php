<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Removes verification from a user who is currently verified on behalf of the organization represented by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#removeuserverification
 */
#[ApiMethod('removeUserVerification', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class RemoveUserVerification extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    public function __construct(
        int $userId
    )
    {
        if ($userId !== null) $this->userId = $userId;
    }

    public static function make(
        int $userId
    ): static
    {
        return new static($userId);
    }
}
