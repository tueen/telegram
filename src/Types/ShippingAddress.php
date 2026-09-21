<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object represents a shipping address.
 *
 * @link https://core.telegram.org/bots/api#shippingaddress
 */
class ShippingAddress extends Type
{
    /**
     * Two-letter ISO 3166-1 alpha-2 country code
     */
    #[Field('country_code', required: true)]
    public private(set) string $countryCode;

    /**
     * State, if applicable
     */
    #[Field('state', required: true)]
    public private(set) string $state;

    /**
     * City
     */
    #[Field('city', required: true)]
    public private(set) string $city;

    /**
     * First line for the address
     */
    #[Field('street_line1', required: true)]
    public private(set) string $streetLine1;

    /**
     * Second line for the address
     */
    #[Field('street_line2', required: true)]
    public private(set) string $streetLine2;

    /**
     * Address post code
     */
    #[Field('post_code', required: true)]
    public private(set) string $postCode;

}
