<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\OwnedGiftType;
use Tueen\Telegram\Types\UniqueGift;
use Tueen\Telegram\Types\User;

/**
 * Describes a unique gift received and owned by a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#ownedgiftunique
 */
class OwnedGiftUnique extends OwnedGift
{
    /**
     * Type of the gift, always "unique"
     */
    #[Field('type', required: true)]
    public private(set) OwnedGiftType|string $type;

    /**
     * Information about the unique gift
     */
    #[Field('gift', required: true)]
    public private(set) UniqueGift $gift;

    /**
     * Optional. Unique identifier of the received gift for the bot; for gifts received on behalf of business accounts only
     */
    #[Field('owned_gift_id', required: false)]
    public private(set) ?string $ownedGiftId = null;

    /**
     * Optional. Sender of the gift if it is a known user
     */
    #[Field('sender_user', required: false)]
    public private(set) ?User $senderUser = null;

    /**
     * Date the gift was sent in Unix time
     */
    #[Field('send_date', required: true)]
    public private(set) int $sendDate;

    /**
     * Optional. True, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
     */
    #[Field('is_saved', required: false)]
    public private(set) ?bool $isSaved = null;

    /**
     * Optional. True, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
     */
    #[Field('can_be_transferred', required: false)]
    public private(set) ?bool $canBeTransferred = null;

    /**
     * Optional. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
     */
    #[Field('transfer_star_count', required: false)]
    public private(set) ?int $transferStarCount = null;

    /**
     * Optional. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
     */
    #[Field('next_transfer_date', required: false)]
    public private(set) ?int $nextTransferDate = null;

}
