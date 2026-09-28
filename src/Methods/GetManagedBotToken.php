<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\StringResult;

/**
 * Use this method to get the token of a managed bot. Returns the token as String on success.
 *
 * @link https://core.telegram.org/bots/api#getmanagedbottoken
 */
#[ApiMethod('getManagedBotToken', 'POST')]
#[ReturnType(StringResult::class, isArray: false)]
class GetManagedBotToken extends Method
{
    /**
     * User identifier of the managed bot whose token will be returned
     */
    #[Field('user_id', required: true)]
    public int $userId;

    public function __construct(
        int $userId,
        mixed ...$extra
    )
    {
        $this->userId = $userId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
