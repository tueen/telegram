<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * This object contains information about an incoming shipping query.
 *
 * @link https://core.telegram.org/bots/api#shippingquery
 */
class ShippingQuery extends Type
{
    /**
     * Unique query identifier
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * User who sent the query
     */
    #[Field('from', required: true)]
    public private(set) User $from;

    /**
     * Bot-specified invoice payload
     */
    #[Field('invoice_payload', required: true)]
    public private(set) string $invoicePayload;

    /**
     * User specified shipping address
     */
    #[Field('shipping_address', required: true)]
    public private(set) ShippingAddress $shippingAddress;

}
