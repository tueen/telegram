<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) string $address;

    /**
     * Optional. Location of the business
     */
    #[Field('location', required: false)]
    private(set) ?Location $location = null;

}
