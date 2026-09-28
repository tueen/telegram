<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) ?string $name = null;

    /**
     * The sticker that represents the unique gift
     */
    #[Field('sticker', required: true)]
    private(set) ?Sticker $sticker = null;

    /**
     * The number of unique gifts that receive this model for every 1000 gift upgrades. Always 0 for crafted gifts.
     */
    #[Field('rarity_per_mille', required: true)]
    private(set) ?int $rarityPerMille = null;

    /**
     * Optional. Rarity of the model if it is a crafted model. Currently, can be "uncommon", "rare", "epic", or "legendary".
     */
    #[Field('rarity', required: false)]
    private(set) UniqueGiftModelRarity|string|null $rarity = null;

}
