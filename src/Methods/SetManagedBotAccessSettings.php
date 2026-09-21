<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the access settings of a managed bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmanagedbotaccesssettings
 */
#[ApiMethod('setManagedBotAccessSettings', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetManagedBotAccessSettings extends Method
{
    /**
     * User identifier of the managed bot whose access settings will be changed
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Pass True if only selected users can access the bot. The bot's owner can always access it.
     */
    #[Field('is_access_restricted', required: true)]
    public bool $isAccessRestricted;

    /**
     * A JSON-serialized list of up to 10 identifiers of users who will have access to the bot in addition to its owner. Ignored if is_access_restricted is False.
     */
    #[Field('added_user_ids', required: false)]
    public ?array $addedUserIds = null;

    public function __construct(
        int $userId,
        bool $isAccessRestricted,
        ?array $addedUserIds = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($isAccessRestricted !== null) $this->isAccessRestricted = $isAccessRestricted;
        if ($addedUserIds !== null) $this->addedUserIds = $addedUserIds;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
