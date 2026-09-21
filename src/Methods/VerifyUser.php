<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Verifies a user on behalf of the organization which is represented by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#verifyuser
 */
#[ApiMethod('verifyUser', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class VerifyUser extends Method
{
    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
     */
    #[Field('custom_description', required: false)]
    public ?string $customDescription = null;

    public function __construct(
        int $userId,
        ?string $customDescription = null
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($customDescription !== null) $this->customDescription = $customDescription;
    }

    public static function make(
        int $userId,
        ?string $customDescription = null
    ): static
    {
        return new static($userId, $customDescription);
    }
}
