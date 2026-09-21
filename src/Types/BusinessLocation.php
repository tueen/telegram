<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Contains information about the location of a Telegram Business account.
 *
 * @link https://core.telegram.org/bots/api#businesslocation
 */
class BusinessLocation extends Type
{
    /**
     * Address of the business
     */
    #[Field('address', required: true)]
    public private(set) string $address;

    /**
     * Optional. Location of the business
     */
    #[Field('location', required: false)]
    public private(set) ?Location $location = null;

}
