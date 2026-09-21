<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Sticker;

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
    public private(set) string $name;

    /**
     * The sticker that represents the unique gift
     */
    #[Field('sticker', required: true)]
    public private(set) Sticker $sticker;

    /**
     * The number of unique gifts that receive this model for every 1000 gifts upgraded
     */
    #[Field('rarity_per_mille', required: true)]
    public private(set) int $rarityPerMille;

}
