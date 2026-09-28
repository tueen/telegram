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
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletewebhook
 *
 * @throws UnauthorizedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('deleteWebhook', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::Unauthorized, TelegramErrorCode::FloodWait])]
class DeleteWebhook extends Method
{
    /**
     * Pass True to drop all pending updates
     */
    #[Field('drop_pending_updates', required: false)]
    public ?bool $dropPendingUpdates = null;

    public function __construct(
        ?bool $dropPendingUpdates = null,
        mixed ...$extra
    )
    {
        if ($dropPendingUpdates !== null) $this->dropPendingUpdates = $dropPendingUpdates;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
