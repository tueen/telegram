<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Types\StarTransactions;

/**
 * Returns the bot's Telegram Star transactions in chronological order. On success, returns a StarTransactions object.
 *
 * @link https://core.telegram.org/bots/api#getstartransactions
 *
 * @throws UnauthorizedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getStarTransactions', 'POST')]
#[ReturnType(StarTransactions::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::Unauthorized, TelegramErrorCode::FloodWait])]
class GetStarTransactions extends Method
{
    /**
     * Number of transactions to skip in the response
     */
    #[Field('offset', required: false)]
    public ?int $offset = null;

    /**
     * The maximum number of transactions to be retrieved. Values between 1-100 are accepted. Defaults to 100.
     */
    #[Field('limit', required: false)]
    public ?int $limit = null;

    public function __construct(
        ?int $offset = null,
        ?int $limit = null,
        mixed ...$extra
    )
    {
        if ($offset !== null) $this->offset = $offset;
        if ($limit !== null) $this->limit = $limit;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
