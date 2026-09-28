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
use Tueen\Telegram\Types\ChatAdministratorRights;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmydefaultadministratorrights
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setMyDefaultAdministratorRights', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class SetMyDefaultAdministratorRights extends Method
{
    /**
     * A JSON-serialized object describing new default administrator rights. If not specified, the default administrator rights will be cleared.
     */
    #[Field('rights', required: false)]
    public ?ChatAdministratorRights $rights = null;

    /**
     * Pass True to change the default administrator rights of the bot in channels. Otherwise, the default administrator rights of the bot for groups and supergroups will be changed.
     */
    #[Field('for_channels', required: false)]
    public ?bool $forChannels = null;

    public function __construct(
        ?ChatAdministratorRights $rights = null,
        ?bool $forChannels = null,
        mixed ...$extra
    )
    {
        if ($rights !== null) $this->rights = $rights;
        if ($forChannels !== null) $this->forChannels = $forChannels;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
