<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\BotAccessSettings;

/**
 * Use this method to get the access settings of a managed bot. Returns a BotAccessSettings object on success.
 *
 * @link https://core.telegram.org/bots/api#getmanagedbotaccesssettings
 */
#[ApiMethod('getManagedBotAccessSettings', 'POST')]
#[ReturnType(BotAccessSettings::class, isArray: false)]
class GetManagedBotAccessSettings extends Method
{
    /**
     * User identifier of the managed bot whose access settings will be returned
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    public function __construct(
        ?int $userId = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
