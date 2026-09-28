<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Transfers an owned unique gift to another user. Requires the can_transfer_and_upgrade_gifts business bot right. Requires can_transfer_stars business bot right if the transfer is paid. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#transfergift
 */
#[ApiMethod('transferGift', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class TransferGift extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Unique identifier of the regular gift that should be transferred
     */
    #[Field('owned_gift_id', required: true)]
    public string $ownedGiftId;

    /**
     * Unique identifier of the chat which will own the gift. The chat must be active in the last 24 hours.
     */
    #[Field('new_owner_chat_id', required: true)]
    public int $newOwnerChatId;

    /**
     * The amount of Telegram Stars that will be paid for the transfer from the business account balance. If positive, then the can_transfer_stars business bot right is required.
     */
    #[Field('star_count', required: false)]
    public ?int $starCount = null;

    public function __construct(
        string $businessConnectionId,
        string $ownedGiftId,
        int $newOwnerChatId,
        ?int $starCount = null,
        mixed ...$extra
    )
    {
        $this->businessConnectionId = $businessConnectionId;
        $this->ownedGiftId = $ownedGiftId;
        $this->newOwnerChatId = $newOwnerChatId;
        if ($starCount !== null) $this->starCount = $starCount;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
