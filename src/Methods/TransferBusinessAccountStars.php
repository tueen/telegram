<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Transfers Telegram Stars from the business account balance to the bot's balance. Requires the can_transfer_stars business bot right. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#transferbusinessaccountstars
 */
#[ApiMethod('transferBusinessAccountStars', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class TransferBusinessAccountStars extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public string $businessConnectionId;

    /**
     * Number of Telegram Stars to transfer; 1-10000
     */
    #[Field('star_count', required: true)]
    public int $starCount;

    public function __construct(
        string $businessConnectionId,
        int $starCount,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($starCount !== null) $this->starCount = $starCount;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
