<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#promotechatmember
 */
#[ApiMethod('promoteChatMember', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class PromoteChatMember extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Unique identifier of the target user
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Pass True if the administrator's presence in the chat is hidden
     */
    #[Field('is_anonymous', required: false)]
    public ?bool $isAnonymous = null;

    /**
     * Pass True if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
     */
    #[Field('can_manage_chat', required: false)]
    public ?bool $canManageChat = null;

    /**
     * Pass True if the administrator can delete messages of other users
     */
    #[Field('can_delete_messages', required: false)]
    public ?bool $canDeleteMessages = null;

    /**
     * Pass True if the administrator can manage video chats
     */
    #[Field('can_manage_video_chats', required: false)]
    public ?bool $canManageVideoChats = null;

    /**
     * Pass True if the administrator can restrict, ban or unban chat members, or access supergroup statistics. For backward compatibility, defaults to True for promotions of channel administrators.
     */
    #[Field('can_restrict_members', required: false)]
    public ?bool $canRestrictMembers = null;

    /**
     * Pass True if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by him)
     */
    #[Field('can_promote_members', required: false)]
    public ?bool $canPromoteMembers = null;

    /**
     * Pass True if the administrator can change chat title, photo and other settings
     */
    #[Field('can_change_info', required: false)]
    public ?bool $canChangeInfo = null;

    /**
     * Pass True if the administrator can invite new users to the chat
     */
    #[Field('can_invite_users', required: false)]
    public ?bool $canInviteUsers = null;

    /**
     * Pass True if the administrator can post stories to the chat
     */
    #[Field('can_post_stories', required: false)]
    public ?bool $canPostStories = null;

    /**
     * Pass True if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
     */
    #[Field('can_edit_stories', required: false)]
    public ?bool $canEditStories = null;

    /**
     * Pass True if the administrator can delete stories posted by other users
     */
    #[Field('can_delete_stories', required: false)]
    public ?bool $canDeleteStories = null;

    /**
     * Pass True if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
     */
    #[Field('can_post_messages', required: false)]
    public ?bool $canPostMessages = null;

    /**
     * Pass True if the administrator can edit messages of other users and can pin messages; for channels only
     */
    #[Field('can_edit_messages', required: false)]
    public ?bool $canEditMessages = null;

    /**
     * Pass True if the administrator can pin messages; for supergroups only
     */
    #[Field('can_pin_messages', required: false)]
    public ?bool $canPinMessages = null;

    /**
     * Pass True if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
     */
    #[Field('can_manage_topics', required: false)]
    public ?bool $canManageTopics = null;

    /**
     * Pass True if the administrator can manage direct messages within the channel and decline suggested posts; for channels only
     */
    #[Field('can_manage_direct_messages', required: false)]
    public ?bool $canManageDirectMessages = null;

    /**
     * Pass True if the administrator can edit the tags of regular members; for groups and supergroups only
     */
    #[Field('can_manage_tags', required: false)]
    public ?bool $canManageTags = null;

    /**
     * Pass True if the administrator can manage chat welcome messages or directly send them in the case of bots
     */
    #[Field('can_send_welcome_messages', required: false)]
    public ?bool $canSendWelcomeMessages = null;

    public function __construct(
        int|string $chatId,
        int $userId,
        ?bool $isAnonymous = null,
        ?bool $canManageChat = null,
        ?bool $canDeleteMessages = null,
        ?bool $canManageVideoChats = null,
        ?bool $canRestrictMembers = null,
        ?bool $canPromoteMembers = null,
        ?bool $canChangeInfo = null,
        ?bool $canInviteUsers = null,
        ?bool $canPostStories = null,
        ?bool $canEditStories = null,
        ?bool $canDeleteStories = null,
        ?bool $canPostMessages = null,
        ?bool $canEditMessages = null,
        ?bool $canPinMessages = null,
        ?bool $canManageTopics = null,
        ?bool $canManageDirectMessages = null,
        ?bool $canManageTags = null,
        ?bool $canSendWelcomeMessages = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($userId !== null) $this->userId = $userId;
        if ($isAnonymous !== null) $this->isAnonymous = $isAnonymous;
        if ($canManageChat !== null) $this->canManageChat = $canManageChat;
        if ($canDeleteMessages !== null) $this->canDeleteMessages = $canDeleteMessages;
        if ($canManageVideoChats !== null) $this->canManageVideoChats = $canManageVideoChats;
        if ($canRestrictMembers !== null) $this->canRestrictMembers = $canRestrictMembers;
        if ($canPromoteMembers !== null) $this->canPromoteMembers = $canPromoteMembers;
        if ($canChangeInfo !== null) $this->canChangeInfo = $canChangeInfo;
        if ($canInviteUsers !== null) $this->canInviteUsers = $canInviteUsers;
        if ($canPostStories !== null) $this->canPostStories = $canPostStories;
        if ($canEditStories !== null) $this->canEditStories = $canEditStories;
        if ($canDeleteStories !== null) $this->canDeleteStories = $canDeleteStories;
        if ($canPostMessages !== null) $this->canPostMessages = $canPostMessages;
        if ($canEditMessages !== null) $this->canEditMessages = $canEditMessages;
        if ($canPinMessages !== null) $this->canPinMessages = $canPinMessages;
        if ($canManageTopics !== null) $this->canManageTopics = $canManageTopics;
        if ($canManageDirectMessages !== null) $this->canManageDirectMessages = $canManageDirectMessages;
        if ($canManageTags !== null) $this->canManageTags = $canManageTags;
        if ($canSendWelcomeMessages !== null) $this->canSendWelcomeMessages = $canSendWelcomeMessages;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
