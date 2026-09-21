<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\StringResult;

/**
 * Use this method to revoke the current token of a managed bot and generate a new one. Returns the new token as String on success.
 *
 * @link https://core.telegram.org/bots/api#replacemanagedbottoken
 */
#[ApiMethod('replaceManagedBotToken', 'POST')]
#[ReturnType(StringResult::class, isArray: false)]
class ReplaceManagedBotToken extends Method
{
    /**
     * User identifier of the managed bot whose token will be replaced
     */
    #[Field('user_id', required: true)]
    public int $userId;

    public function __construct(
        int $userId,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
