<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents one shipping option.
 *
 * @link https://core.telegram.org/bots/api#shippingoption
 */
class ShippingOption extends Type
{
    /**
     * Shipping option identifier
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * Option title
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * List of price portions
     * @var LabeledPrice[]|null
     */
    #[Field('prices', required: true)]
    #[ArrayOf(LabeledPrice::class)]
    public private(set) array $prices;

}
