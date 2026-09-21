<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\UniqueGiftModel;
use Tueen\Telegram\Types\UniqueGiftSymbol;
use Tueen\Telegram\Types\UniqueGiftBackdrop;
use Tueen\Telegram\Types\UniqueGiftColors;
use Tueen\Telegram\Types\Chat;

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
    public private(set) string $giftId;

    /**
     * Human-readable name of the regular gift from which this unique gift was upgraded
     */
    #[Field('base_name', required: true)]
    public private(set) string $baseName;

    /**
     * Unique name of the gift. This name can be used in https://t.me/nft/... links and story areas.
     */
    #[Field('name', required: true)]
    public private(set) string $name;

    /**
     * Unique number of the upgraded gift among gifts upgraded from the same regular gift
     */
    #[Field('number', required: true)]
    public private(set) int $number;

    /**
     * Model of the gift
     */
    #[Field('model', required: true)]
    public private(set) UniqueGiftModel $model;

    /**
     * Symbol of the gift
     */
    #[Field('symbol', required: true)]
    public private(set) UniqueGiftSymbol $symbol;

    /**
     * Backdrop of the gift
     */
    #[Field('backdrop', required: true)]
    public private(set) UniqueGiftBackdrop $backdrop;

    /**
     * Optional. True, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
     */
    #[Field('is_premium', required: false)]
    public private(set) ?bool $isPremium = null;

    /**
     * Optional. True, if the gift was used to craft another gift and isn't available anymore
     */
    #[Field('is_burned', required: false)]
    public private(set) ?bool $isBurned = null;

    /**
     * Optional. True, if the gift is assigned from the TON blockchain and can't be resold or transferred in Telegram
     */
    #[Field('is_from_blockchain', required: false)]
    public private(set) ?bool $isFromBlockchain = null;

    /**
     * Optional. The color scheme that can be used by the gift's owner for the chat's name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
     */
    #[Field('colors', required: false)]
    public private(set) ?UniqueGiftColors $colors = null;

    /**
     * Optional. Information about the chat that published the gift
     */
    #[Field('publisher_chat', required: false)]
    public private(set) ?Chat $publisherChat = null;

}
