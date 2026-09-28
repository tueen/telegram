<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

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
    private(set) ?string $name = null;

    /**
     * Colors of the backdrop
     */
    #[Field('colors', required: true)]
    private(set) ?UniqueGiftBackdropColors $colors = null;

    /**
     * The number of unique gifts that receive this backdrop for every 1000 gifts upgraded
     */
    #[Field('rarity_per_mille', required: true)]
    private(set) ?int $rarityPerMille = null;

}
