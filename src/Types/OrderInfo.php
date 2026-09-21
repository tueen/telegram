<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\ShippingAddress;

/**
 * This object represents information about an order.
 *
 * @link https://core.telegram.org/bots/api#orderinfo
 */
class OrderInfo extends Type
{
    /**
     * Optional. User name
     */
    #[Field('name', required: false)]
    public private(set) ?string $name = null;

    /**
     * Optional. User's phone number
     */
    #[Field('phone_number', required: false)]
    public private(set) ?string $phoneNumber = null;

    /**
     * Optional. User email
     */
    #[Field('email', required: false)]
    public private(set) ?string $email = null;

    /**
     * Optional. User shipping address
     */
    #[Field('shipping_address', required: false)]
    public private(set) ?ShippingAddress $shippingAddress = null;

}
