<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\SuggestedPostPrice;

/**
 * Contains information about a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostinfo
 */
class SuggestedPostInfo extends Type
{
    /**
     * State of the suggested post. Currently, it can be one of "pending", "approved", "declined".
     */
    #[Field('state', required: true)]
    public private(set) string $state;

    /**
     * Optional. Proposed price of the post. If the field is omitted, then the post is unpaid.
     */
    #[Field('price', required: false)]
    public private(set) ?SuggestedPostPrice $price = null;

    /**
     * Optional. Proposed send date of the post. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user or administrator who approves it.
     */
    #[Field('send_date', required: false)]
    public private(set) ?int $sendDate = null;

}
