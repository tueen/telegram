<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\ChatPermissions;

/**
 * Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#restrictchatmember
 */
#[ApiMethod('restrictChatMember', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class RestrictChatMember extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * A JSON-serialized object for new user permissions
     */
    #[Field('permissions', required: true)]
    public ChatPermissions $permissions;

    /**
     * Pass True if chat permissions are set independently. Otherwise, the can_send_other_messages and can_add_web_page_previews permissions will imply the can_send_messages, can_send_audios, can_send_documents, can_send_photos, can_send_videos, can_send_video_notes, and can_send_voice_notes permissions; the can_send_polls permission will imply the can_send_messages permission.
     */
    #[Field('use_independent_chat_permissions', required: false)]
    public ?bool $useIndependentChatPermissions = null;

    /**
     * Date when restrictions will be lifted for the user; Unix time. If user is restricted for more than 366 days or less than 30 seconds from the current time, they are considered to be restricted forever.
     */
    #[Field('until_date', required: false)]
    public ?int $untilDate = null;

    public function __construct(
        int|string $chatId,
        int $userId,
        ChatPermissions $permissions,
        ?bool $useIndependentChatPermissions = null,
        ?int $untilDate = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($permissions !== null) $this->permissions = $permissions;
        if ($useIndependentChatPermissions !== null) $this->useIndependentChatPermissions = $useIndependentChatPermissions;
        if ($untilDate !== null) $this->untilDate = $untilDate;
    }

    public static function make(
        int|string $chatId,
        int $userId,
        ChatPermissions $permissions,
        ?bool $useIndependentChatPermissions = null,
        ?int $untilDate = null
    ): static
    {
        return new static($chatId, $userId, $permissions, $useIndependentChatPermissions, $untilDate);
    }
}
