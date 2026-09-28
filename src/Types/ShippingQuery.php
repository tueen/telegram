<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

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
    private(set) ?string $id = null;

    /**
     * User who sent the query
     */
    #[Field('from', required: true)]
    private(set) ?User $from = null;

    /**
     * Bot-specified invoice payload
     */
    #[Field('invoice_payload', required: true)]
    private(set) ?string $invoicePayload = null;

    /**
     * User specified shipping address
     */
    #[Field('shipping_address', required: true)]
    private(set) ?ShippingAddress $shippingAddress = null;

}
