<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Changes the username of a managed business account. Requires the can_change_username business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountusername
 */
#[ApiMethod('setBusinessAccountUsername', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class SetBusinessAccountUsername extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * The new value of the username for the business account; 0-32 characters
     */
    #[Field('username', required: false)]
    public ?string $username = null;

    public function __construct(
        string $businessConnectionId,
        ?string $username = null
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($username !== null) $this->username = $username;
    }

    public static function make(
        string $businessConnectionId,
        ?string $username = null
    ): static
    {
        return new static($businessConnectionId, $username);
    }
}
