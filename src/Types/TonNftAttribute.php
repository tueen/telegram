<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object represents an attribute of an NFT in the TON blockchain.
 *
 * @link https://core.telegram.org/bots/api#tonnftattribute
 */
class TonNftAttribute extends Type
{
    /**
     * Trait type of the attribute
     */
    #[Field('trait_type', required: true)]
    private(set) ?string $traitType = null;

    /**
     * Value of the attribute
     */
    #[Field('value', required: true)]
    private(set) ?string $value = null;

}
