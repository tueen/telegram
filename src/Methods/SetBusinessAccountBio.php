<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Changes the bio of a managed business account. Requires the can_change_bio business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountbio
 */
#[ApiMethod('setBusinessAccountBio', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetBusinessAccountBio extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * The new value of the bio for the business account; 0-140 characters
     */
    #[Field('bio', required: false)]
    public ?string $bio = null;

    public function __construct(
        string $businessConnectionId,
        ?string $bio = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($bio !== null) $this->bio = $bio;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
