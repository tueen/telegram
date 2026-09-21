<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Upgrades a given regular gift to a unique gift. Requires the can_transfer_and_upgrade_gifts business bot right. Additionally requires the can_transfer_stars business bot right if the upgrade is paid. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#upgradegift
 */
#[ApiMethod('upgradeGift', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class UpgradeGift extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the regular gift that should be upgraded to a unique one
     */
    #[Field('owned_gift_id', required: true)]
    public string $ownedGiftId;

    /**
     * Pass True to keep the original gift text, sender and receiver in the upgraded gift
     */
    #[Field('keep_original_details', required: false)]
    public ?bool $keepOriginalDetails = null;

    /**
     * The amount of Telegram Stars that will be paid for the upgrade from the business account balance. If gift.prepaid_upgrade_star_count > 0, then pass 0, otherwise, the can_transfer_stars business bot right is required and gift.upgrade_star_count must be passed.
     */
    #[Field('star_count', required: false)]
    public ?int $starCount = null;

    public function __construct(
        string $businessConnectionId,
        string $ownedGiftId,
        ?bool $keepOriginalDetails = null,
        ?int $starCount = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($ownedGiftId !== null) $this->ownedGiftId = $ownedGiftId;
        if ($keepOriginalDetails !== null) $this->keepOriginalDetails = $keepOriginalDetails;
        if ($starCount !== null) $this->starCount = $starCount;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
