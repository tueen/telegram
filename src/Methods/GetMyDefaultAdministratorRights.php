<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\ChatAdministratorRights;

/**
 * Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 *
 * @link https://core.telegram.org/bots/api#getmydefaultadministratorrights
 */
#[ApiMethod('getMyDefaultAdministratorRights', 'POST')]
#[ReturnType(ChatAdministratorRights::class, isArray: false)]
class GetMyDefaultAdministratorRights extends Method
{
    /**
     * Pass True to get default administrator rights of the bot in channels. Otherwise, default administrator rights of the bot for groups and supergroups will be returned.
     */
    #[Field('for_channels', required: false)]
    public ?bool $forChannels = null;

    public function __construct(
        ?bool $forChannels = null,
        mixed ...$extra
    )
    {
        if ($forChannels !== null) $this->forChannels = $forChannels;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
