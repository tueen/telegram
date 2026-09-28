<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ChatMemberStatus;

/**
 * Represents a chat member that is under certain restrictions in the chat. Supergroups only.
 *
 * @link https://core.telegram.org/bots/api#chatmemberrestricted
 */
class ChatMemberRestricted extends ChatMember
{
    /**
     * The member's status in the chat, always "restricted"
     */
    #[Field('status', required: true)]
    private(set) ChatMemberStatus|string|null $status = null;

    /**
     * Optional. Tag of the member
     */
    #[Field('tag', required: false)]
    private(set) ?string $tag = null;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    private(set) ?User $user = null;

    /**
     * True, if the user is a member of the chat at the moment of the request
     */
    #[Field('is_member', required: true)]
    private(set) ?bool $isMember = null;

    /**
     * True, if the user is allowed to send text messages, rich messages, contacts, giveaways, giveaway winners, invoices, locations and venues
     */
    #[Field('can_send_messages', required: true)]
    private(set) ?bool $canSendMessages = null;

    /**
     * True, if the user is allowed to send audios
     */
    #[Field('can_send_audios', required: true)]
    private(set) ?bool $canSendAudios = null;

    /**
     * True, if the user is allowed to send documents
     */
    #[Field('can_send_documents', required: true)]
    private(set) ?bool $canSendDocuments = null;

    /**
     * True, if the user is allowed to send photos
     */
    #[Field('can_send_photos', required: true)]
    private(set) ?bool $canSendPhotos = null;

    /**
     * True, if the user is allowed to send videos
     */
    #[Field('can_send_videos', required: true)]
    private(set) ?bool $canSendVideos = null;

    /**
     * True, if the user is allowed to send video notes
     */
    #[Field('can_send_video_notes', required: true)]
    private(set) ?bool $canSendVideoNotes = null;

    /**
     * True, if the user is allowed to send voice notes
     */
    #[Field('can_send_voice_notes', required: true)]
    private(set) ?bool $canSendVoiceNotes = null;

    /**
     * True, if the user is allowed to send polls and checklists
     */
    #[Field('can_send_polls', required: true)]
    private(set) ?bool $canSendPolls = null;

    /**
     * True, if the user is allowed to send animations, games, stickers and use inline bots
     */
    #[Field('can_send_other_messages', required: true)]
    private(set) ?bool $canSendOtherMessages = null;

    /**
     * True, if the user is allowed to add web page previews to their messages
     */
    #[Field('can_add_web_page_previews', required: true)]
    private(set) ?bool $canAddWebPagePreviews = null;

    /**
     * True, if the user is allowed to react to messages
     */
    #[Field('can_react_to_messages', required: true)]
    private(set) ?bool $canReactToMessages = null;

    /**
     * True, if the user is allowed to edit their own tag
     */
    #[Field('can_edit_tag', required: true)]
    private(set) ?bool $canEditTag = null;

    /**
     * True, if the user is allowed to change the chat title, photo and other settings
     */
    #[Field('can_change_info', required: true)]
    private(set) ?bool $canChangeInfo = null;

    /**
     * True, if the user is allowed to invite new users to the chat
     */
    #[Field('can_invite_users', required: true)]
    private(set) ?bool $canInviteUsers = null;

    /**
     * True, if the user is allowed to pin messages
     */
    #[Field('can_pin_messages', required: true)]
    private(set) ?bool $canPinMessages = null;

    /**
     * True, if the user is allowed to create forum topics
     */
    #[Field('can_manage_topics', required: true)]
    private(set) ?bool $canManageTopics = null;

    /**
     * Date when restrictions will be lifted for this user; Unix time. If 0, then the user is restricted forever.
     */
    #[Field('until_date', required: true)]
    private(set) ?int $untilDate = null;

}
