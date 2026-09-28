<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) ?string $countryCode = null;

    /**
     * State, if applicable
     */
    #[Field('state', required: true)]
    private(set) ?string $state = null;

    /**
     * City
     */
    #[Field('city', required: true)]
    private(set) ?string $city = null;

    /**
     * First line for the address
     */
    #[Field('street_line1', required: true)]
    private(set) ?string $streetLine1 = null;

    /**
     * Second line for the address
     */
    #[Field('street_line2', required: true)]
    private(set) ?string $streetLine2 = null;

    /**
     * Address post code
     */
    #[Field('post_code', required: true)]
    private(set) ?string $postCode = null;

}
