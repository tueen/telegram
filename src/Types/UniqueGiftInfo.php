<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\UniqueGiftInfoOrigin;

/**
 * Describes a service message about a unique gift that was sent or received.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftinfo
 */
class UniqueGiftInfo extends Type
{
    /**
     * Information about the gift
     */
    #[Field('gift', required: true)]
    private(set) UniqueGift $gift;

    /**
     * Origin of the gift. Currently, either "upgrade" for gifts upgraded from regular gifts, "transfer" for gifts transferred from other users or channels, "resale" for gifts bought from other users, "gifted_upgrade" for upgrades purchased after the gift was sent, or "offer" for gifts bought or sold through gift purchase offers.
     */
    #[Field('origin', required: true)]
    private(set) UniqueGiftInfoOrigin|string $origin;

    /**
     * Optional. Text of the message that was added to the gift
     */
    #[Field('text', required: false)]
    private(set) ?string $text = null;

    /**
     * Optional. Special entities that appear in the text
     * @var MessageEntity[]|null
     */
    #[Field('entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $entities = null;

    /**
     * Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
     */
    #[Field('is_private', required: false)]
    private(set) ?bool $isPrivate = null;

    /**
     * Optional. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of "XTR" for Telegram Stars or "TON" for TON grams.
     */
    #[Field('last_resale_currency', required: false)]
    private(set) ?string $lastResaleCurrency = null;

    /**
     * Optional. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanograms
     */
    #[Field('last_resale_amount', required: false)]
    private(set) ?int $lastResaleAmount = null;

    /**
     * Optional. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
     */
    #[Field('owned_gift_id', required: false)]
    private(set) ?string $ownedGiftId = null;

    /**
     * Optional. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
     */
    #[Field('transfer_star_count', required: false)]
    private(set) ?int $transferStarCount = null;

    /**
     * Optional. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
     */
    #[Field('next_transfer_date', required: false)]
    private(set) ?int $nextTransferDate = null;

}
