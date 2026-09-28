<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\AcceptedGiftTypes;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Changes the privacy settings pertaining to incoming gifts in a managed business account. Requires the can_change_gift_settings business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountgiftsettings
 */
#[ApiMethod('setBusinessAccountGiftSettings', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetBusinessAccountGiftSettings extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Pass True if a button for sending a gift to the user or by the business account must always be shown in the input field
     */
    #[Field('show_gift_button', required: true)]
    public bool $showGiftButton;

    /**
     * Types of gifts accepted by the business account
     */
    #[Field('accepted_gift_types', required: true)]
    public AcceptedGiftTypes $acceptedGiftTypes;

    public function __construct(
        string $businessConnectionId,
        bool $showGiftButton,
        AcceptedGiftTypes $acceptedGiftTypes,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($showGiftButton !== null) $this->showGiftButton = $showGiftButton;
        if ($acceptedGiftTypes !== null) $this->acceptedGiftTypes = $acceptedGiftTypes;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
