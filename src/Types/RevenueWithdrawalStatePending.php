<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * The withdrawal is in progress.
 *
 * @link https://core.telegram.org/bots/api#revenuewithdrawalstatepending
 */
class RevenueWithdrawalStatePending extends RevenueWithdrawalState
{
    /**
     * Type of the state, always "pending"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

}
