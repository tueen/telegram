<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Changes the first and last name of a managed business account. Requires the can_change_name business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountname
 */
#[ApiMethod('setBusinessAccountName', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetBusinessAccountName extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * The new value of the first name for the business account; 1-64 characters
     */
    #[Field('first_name', required: true)]
    public string $firstName;

    /**
     * The new value of the last name for the business account; 0-64 characters
     */
    #[Field('last_name', required: false)]
    public ?string $lastName = null;

    public function __construct(
        string $businessConnectionId,
        string $firstName,
        ?string $lastName = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($firstName !== null) $this->firstName = $firstName;
        if ($lastName !== null) $this->lastName = $lastName;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
