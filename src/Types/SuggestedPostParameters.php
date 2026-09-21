<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\SuggestedPostPrice;

/**
 * Contains parameters of a post that is being suggested by the bot.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostparameters
 */
class SuggestedPostParameters extends Type
{
    /**
     * Optional. Proposed price for the post. If the field is omitted, then the post is unpaid.
     */
    #[Field('price', required: false)]
    public private(set) ?SuggestedPostPrice $price = null;

    /**
     * Optional. Proposed send date of the post. If specified, then the date must be between 300 second and 2678400 seconds (30 days) in the future. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user who approves it.
     */
    #[Field('send_date', required: false)]
    public private(set) ?int $sendDate = null;

}
