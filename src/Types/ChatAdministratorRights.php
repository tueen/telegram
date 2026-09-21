<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Represents the rights of an administrator in a chat.
 *
 * @link https://core.telegram.org/bots/api#chatadministratorrights
 */
class ChatAdministratorRights extends Type
{
    /**
     * True, if the user's presence in the chat is hidden
     */
    #[Field('is_anonymous', required: true)]
    public private(set) bool $isAnonymous;

    /**
     * True, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
     */
    #[Field('can_manage_chat', required: true)]
    public private(set) bool $canManageChat;

    /**
     * True, if the administrator can delete messages of other users
     */
    #[Field('can_delete_messages', required: true)]
    public private(set) bool $canDeleteMessages;

    /**
     * True, if the administrator can manage video chats
     */
    #[Field('can_manage_video_chats', required: true)]
    public private(set) bool $canManageVideoChats;

    /**
     * True, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
     */
    #[Field('can_restrict_members', required: true)]
    public private(set) bool $canRestrictMembers;

    /**
     * True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user)
     */
    #[Field('can_promote_members', required: true)]
    public private(set) bool $canPromoteMembers;

    /**
     * True, if the user is allowed to change the chat title, photo and other settings
     */
    #[Field('can_change_info', required: true)]
    public private(set) bool $canChangeInfo;

    /**
     * True, if the user is allowed to invite new users to the chat
     */
    #[Field('can_invite_users', required: true)]
    public private(set) bool $canInviteUsers;

    /**
     * True, if the administrator can post stories to the chat
     */
    #[Field('can_post_stories', required: true)]
    public private(set) bool $canPostStories;

    /**
     * True, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
     */
    #[Field('can_edit_stories', required: true)]
    public private(set) bool $canEditStories;

    /**
     * True, if the administrator can delete stories posted by other users
     */
    #[Field('can_delete_stories', required: true)]
    public private(set) bool $canDeleteStories;

    /**
     * Optional. True, if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
     */
    #[Field('can_post_messages', required: false)]
    public private(set) ?bool $canPostMessages = null;

    /**
     * Optional. True, if the administrator can edit messages of other users and can pin messages; for channels only
     */
    #[Field('can_edit_messages', required: false)]
    public private(set) ?bool $canEditMessages = null;

    /**
     * Optional. True, if the user is allowed to pin messages; for groups and supergroups only
     */
    #[Field('can_pin_messages', required: false)]
    public private(set) ?bool $canPinMessages = null;

    /**
     * Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
     */
    #[Field('can_manage_topics', required: false)]
    public private(set) ?bool $canManageTopics = null;

    /**
     * Optional. True, if the administrator can manage direct messages of the channel and decline suggested posts; for channels only
     */
    #[Field('can_manage_direct_messages', required: false)]
    public private(set) ?bool $canManageDirectMessages = null;

    /**
     * Optional. True, if the administrator can edit the tags of regular members; for groups and supergroups only
     */
    #[Field('can_manage_tags', required: false)]
    public private(set) ?bool $canManageTags = null;

    /**
     * True, if the administrator can manage chat welcome messages or directly send them in the case of bots
     */
    #[Field('can_send_welcome_messages', required: true)]
    public private(set) bool $canSendWelcomeMessages;

}
