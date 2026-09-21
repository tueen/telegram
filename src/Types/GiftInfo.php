<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Gift;
use Tueen\Telegram\Types\MessageEntity;

/**
 * Describes a service message about a regular gift that was sent or received.
 *
 * @link https://core.telegram.org/bots/api#giftinfo
 */
class GiftInfo extends Type
{
    /**
     * Information about the gift
     */
    #[Field('gift', required: true)]
    public private(set) Gift $gift;

    /**
     * Optional. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
     */
    #[Field('owned_gift_id', required: false)]
    public private(set) ?string $ownedGiftId = null;

    /**
     * Optional. Number of Telegram Stars that can be claimed by the receiver by converting the gift; omitted if conversion to Telegram Stars is impossible
     */
    #[Field('convert_star_count', required: false)]
    public private(set) ?int $convertStarCount = null;

    /**
     * Optional. Number of Telegram Stars that were prepaid for the ability to upgrade the gift
     */
    #[Field('prepaid_upgrade_star_count', required: false)]
    public private(set) ?int $prepaidUpgradeStarCount = null;

    /**
     * Optional. True, if the gift's upgrade was purchased after the gift was sent
     */
    #[Field('is_upgrade_separate', required: false)]
    public private(set) ?bool $isUpgradeSeparate = null;

    /**
     * Optional. True, if the gift can be upgraded to a unique gift
     */
    #[Field('can_be_upgraded', required: false)]
    public private(set) ?bool $canBeUpgraded = null;

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
     * Optional. Unique number reserved for this gift when upgraded. See the number field in UniqueGift.
     */
    #[Field('unique_gift_number', required: false)]
    public private(set) ?int $uniqueGiftNumber = null;

}
