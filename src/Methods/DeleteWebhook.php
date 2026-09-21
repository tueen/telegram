<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletewebhook
 */
#[ApiMethod('deleteWebhook', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class DeleteWebhook extends Method
{
    /**
     * Pass True to drop all pending updates
     */
    #[Field('drop_pending_updates', required: false)]
    public ?bool $dropPendingUpdates = null;

    public function __construct(
        ?bool $dropPendingUpdates = null
    )
    {
        if ($dropPendingUpdates !== null) $this->dropPendingUpdates = $dropPendingUpdates;
    }

    public static function make(
        ?bool $dropPendingUpdates = null
    ): static
    {
        return new static($dropPendingUpdates);
    }
}
