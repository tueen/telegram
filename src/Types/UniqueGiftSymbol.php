<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object describes the symbol shown on the pattern of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftsymbol
 */
class UniqueGiftSymbol extends Type
{
    /**
     * Name of the symbol
     */
    #[Field('name', required: true)]
    private(set) ?string $name = null;

    /**
     * The sticker that represents the unique gift
     */
    #[Field('sticker', required: true)]
    private(set) ?Sticker $sticker = null;

    /**
     * The number of unique gifts that receive this model for every 1000 gifts upgraded
     */
    #[Field('rarity_per_mille', required: true)]
    private(set) ?int $rarityPerMille = null;

}
