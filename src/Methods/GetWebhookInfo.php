<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\WebhookInfo;

/**
 * Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 *
 * @link https://core.telegram.org/bots/api#getwebhookinfo
 */
#[ApiMethod('getWebhookInfo', 'POST')]
#[ReturnType(WebhookInfo::class, isArray: false)]
class GetWebhookInfo extends Method
{


    public function __construct(
        mixed ...$extra
    )
    {
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
