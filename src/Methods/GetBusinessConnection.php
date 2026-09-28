<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\BusinessConnection;

/**
 * Use this method to get information about the connection of the bot with a business account. Returns a BusinessConnection object on success.
 *
 * @link https://core.telegram.org/bots/api#getbusinessconnection
 *
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getBusinessConnection', 'POST')]
#[ReturnType(BusinessConnection::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class GetBusinessConnection extends Method
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('business_connection_id', required: true)]
    public ?string $businessConnectionId = null;

    public function __construct(
        ?string $businessConnectionId = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
