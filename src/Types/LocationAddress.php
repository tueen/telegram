<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * Describes the physical address of a location.
 *
 * @link https://core.telegram.org/bots/api#locationaddress
 */
class LocationAddress extends Type
{
    /**
     * The two-letter ISO 3166-1 alpha-2 country code of the country where the location is located
     */
    #[Field('country_code', required: true)]
    public private(set) string $countryCode;

    /**
     * Optional. State of the location
     */
    #[Field('state', required: false)]
    public private(set) ?string $state = null;

    /**
     * Optional. City of the location
     */
    #[Field('city', required: false)]
    public private(set) ?string $city = null;

    /**
     * Optional. Street address of the location
     */
    #[Field('street', required: false)]
    public private(set) ?string $street = null;

}
