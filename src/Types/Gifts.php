<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Gift;

/**
 * This object represent a list of gifts.
 *
 * @link https://core.telegram.org/bots/api#gifts
 */
class Gifts extends Type
{
    /**
     * The list of gifts
     * @var Gift[]|null
     */
    #[Field('gifts', required: true)]
    #[ArrayOf(Gift::class)]
    public private(set) array $gifts;

}
