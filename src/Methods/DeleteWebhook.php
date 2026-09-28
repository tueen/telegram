<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletewebhook
 */
#[ApiMethod('deleteWebhook', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
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
