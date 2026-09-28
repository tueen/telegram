<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Types\ChatPermissions;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatpermissions
 *
 * @throws ChatNotFoundException
 * @throws NotEnoughRightsException
 * @throws BotKickedException
 * @throws ApiException
 */
#[ApiMethod('setChatPermissions', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::NotEnoughRights, TelegramErrorCode::BotKicked])]
class SetChatPermissions extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * A JSON-serialized object for new default chat permissions
     */
    #[Field('permissions', required: true)]
    public ?ChatPermissions $permissions = null;

    /**
     * Pass True if chat permissions are set independently. Otherwise, the can_send_other_messages and can_add_web_page_previews permissions will imply the can_send_messages, can_send_audios, can_send_documents, can_send_photos, can_send_videos, can_send_video_notes, and can_send_voice_notes permissions; the can_send_polls permission will imply the can_send_messages permission.
     */
    #[Field('use_independent_chat_permissions', required: false)]
    public ?bool $useIndependentChatPermissions = null;

    public function __construct(
        int|string|null $chatId = null,
        ?ChatPermissions $permissions = null,
        ?bool $useIndependentChatPermissions = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($permissions !== null) $this->permissions = $permissions;
        if ($useIndependentChatPermissions !== null) $this->useIndependentChatPermissions = $useIndependentChatPermissions;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
