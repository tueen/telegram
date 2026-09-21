<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\UniqueGiftModelRarity;

/**
 * This object describes the model of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftmodel
 */
class UniqueGiftModel extends Type
{
    /**
     * Name of the model
     */
    #[Field('name', required: true)]
    public private(set) string $name;

    /**
     * The sticker that represents the unique gift
     */
    #[Field('sticker', required: true)]
    public private(set) Sticker $sticker;

    /**
     * The number of unique gifts that receive this model for every 1000 gift upgrades. Always 0 for crafted gifts.
     */
    #[Field('rarity_per_mille', required: true)]
    public private(set) int $rarityPerMille;

    /**
     * Optional. Rarity of the model if it is a crafted model. Currently, can be "uncommon", "rare", "epic", or "legendary".
     */
    #[Field('rarity', required: false)]
    public private(set) UniqueGiftModelRarity|string|null $rarity = null;

}
