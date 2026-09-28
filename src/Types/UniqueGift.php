<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes a unique gift that was upgraded from a regular gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegift
 */
class UniqueGift extends Type
{
    /**
     * Identifier of the regular gift from which the gift was upgraded
     */
    #[Field('gift_id', required: true)]
    private(set) ?string $giftId = null;

    /**
     * Human-readable name of the regular gift from which this unique gift was upgraded
     */
    #[Field('base_name', required: true)]
    private(set) ?string $baseName = null;

    /**
     * Unique name of the gift. This name can be used in https://t.me/nft/... links and story areas.
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

    /**
     * Unique number of the upgraded gift among gifts upgraded from the same regular gift
     */
    #[Field('number', required: true)]
    private(set) ?int $number = null;

    /**
     * Model of the gift
     */
    #[Field('model', required: true)]
    private(set) ?UniqueGiftModel $model = null;

    /**
     * Symbol of the gift
     */
    #[Field('symbol', required: true)]
    private(set) ?UniqueGiftSymbol $symbol = null;

    /**
     * Backdrop of the gift
     */
    #[Field('backdrop', required: true)]
    private(set) ?UniqueGiftBackdrop $backdrop = null;

    /**
     * Optional. True, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
     */
    #[Field('is_premium', required: false)]
    private(set) ?bool $isPremium = null;

    /**
     * Optional. True, if the gift was used to craft another gift and isn't available anymore
     */
    #[Field('is_burned', required: false)]
    private(set) ?bool $isBurned = null;

    /**
     * Optional. True, if the gift is assigned from the TON blockchain and can't be resold or transferred in Telegram
     */
    #[Field('is_from_blockchain', required: false)]
    private(set) ?bool $isFromBlockchain = null;

    /**
     * Optional. The color scheme that can be used by the gift's owner for the chat's name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
     */
    #[Field('colors', required: false)]
    private(set) ?UniqueGiftColors $colors = null;

    /**
     * Optional. Information about the chat that published the gift
     */
    #[Field('publisher_chat', required: false)]
    private(set) ?Chat $publisherChat = null;

}
