<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\OwnedGiftType;

/**
 * Describes a regular gift owned by a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#ownedgiftregular
 */
class OwnedGiftRegular extends OwnedGift
{
    /**
     * Type of the gift, always "regular"
     */
    #[Field('type', required: true)]
    public private(set) OwnedGiftType|string $type;

    /**
     * Information about the regular gift
     */
    #[Field('gift', required: true)]
    public private(set) Gift $gift;

    /**
     * Optional. Unique identifier of the gift for the bot; for gifts received on behalf of business accounts only
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
     * Optional. Text of the message that was added to the gift
     */
    #[Field('text', required: false)]
    public private(set) ?string $text = null;

    /**
     * Optional. Special entities that appear in the text
     * @var MessageEntity[]|null
     */
    #[Field('entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $entities = null;

    /**
     * Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
     */
    #[Field('is_private', required: false)]
    public private(set) ?bool $isPrivate = null;

    /**
     * Optional. True, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
     */
    #[Field('is_saved', required: false)]
    public private(set) ?bool $isSaved = null;

    /**
     * Optional. True, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
     */
    #[Field('can_be_upgraded', required: false)]
    public private(set) ?bool $canBeUpgraded = null;

    /**
     * Optional. True, if the gift was refunded and isn't available anymore
     */
    #[Field('was_refunded', required: false)]
    public private(set) ?bool $wasRefunded = null;

    /**
     * Optional. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
     */
    #[Field('convert_star_count', required: false)]
    public private(set) ?int $convertStarCount = null;

    /**
     * Optional. Number of Telegram Stars that were paid for the ability to upgrade the gift
     */
    #[Field('prepaid_upgrade_star_count', required: false)]
    public private(set) ?int $prepaidUpgradeStarCount = null;

    /**
     * Optional. True, if the gift's upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
     */
    #[Field('is_upgrade_separate', required: false)]
    public private(set) ?bool $isUpgradeSeparate = null;

    /**
     * Optional. Unique number reserved for this gift when upgraded. See the number field in UniqueGift.
     */
    #[Field('unique_gift_number', required: false)]
    public private(set) ?int $uniqueGiftNumber = null;

}
