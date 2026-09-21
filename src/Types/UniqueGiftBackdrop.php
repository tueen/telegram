<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\UniqueGiftBackdropColors;

/**
 * This object describes the backdrop of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftbackdrop
 */
class UniqueGiftBackdrop extends Type
{
    /**
     * Name of the backdrop
     */
    #[Field('name', required: true)]
    public private(set) string $name;

    /**
     * Colors of the backdrop
     */
    #[Field('colors', required: true)]
    public private(set) UniqueGiftBackdropColors $colors;

    /**
     * The number of unique gifts that receive this backdrop for every 1000 gifts upgraded
     */
    #[Field('rarity_per_mille', required: true)]
    public private(set) int $rarityPerMille;

}
