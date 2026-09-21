<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\User;

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
    public private(set) string $status;

    /**
     * Optional. Tag of the member
     */
    #[Field('tag', required: false)]
    public private(set) ?string $tag = null;

    /**
     * Information about the user
     */
    #[Field('user', required: true)]
    public private(set) User $user;

    /**
     * True, if the user is a member of the chat at the moment of the request
     */
    #[Field('is_member', required: true)]
    public private(set) bool $isMember;

    /**
     * True, if the user is allowed to send text messages, rich messages, contacts, giveaways, giveaway winners, invoices, locations and venues
     */
    #[Field('can_send_messages', required: true)]
    public private(set) bool $canSendMessages;

    /**
     * True, if the user is allowed to send audios
     */
    #[Field('can_send_audios', required: true)]
    public private(set) bool $canSendAudios;

    /**
     * True, if the user is allowed to send documents
     */
    #[Field('can_send_documents', required: true)]
    public private(set) bool $canSendDocuments;

    /**
     * True, if the user is allowed to send photos
     */
    #[Field('can_send_photos', required: true)]
    public private(set) bool $canSendPhotos;

    /**
     * True, if the user is allowed to send videos
     */
    #[Field('can_send_videos', required: true)]
    public private(set) bool $canSendVideos;

    /**
     * True, if the user is allowed to send video notes
     */
    #[Field('can_send_video_notes', required: true)]
    public private(set) bool $canSendVideoNotes;

    /**
     * True, if the user is allowed to send voice notes
     */
    #[Field('can_send_voice_notes', required: true)]
    public private(set) bool $canSendVoiceNotes;

    /**
     * True, if the user is allowed to send polls and checklists
     */
    #[Field('can_send_polls', required: true)]
    public private(set) bool $canSendPolls;

    /**
     * True, if the user is allowed to send animations, games, stickers and use inline bots
     */
    #[Field('can_send_other_messages', required: true)]
    public private(set) bool $canSendOtherMessages;

    /**
     * True, if the user is allowed to add web page previews to their messages
     */
    #[Field('can_add_web_page_previews', required: true)]
    public private(set) bool $canAddWebPagePreviews;

    /**
     * True, if the user is allowed to react to messages
     */
    #[Field('can_react_to_messages', required: true)]
    public private(set) bool $canReactToMessages;

    /**
     * True, if the user is allowed to edit their own tag
     */
    #[Field('can_edit_tag', required: true)]
    public private(set) bool $canEditTag;

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
     * True, if the user is allowed to pin messages
     */
    #[Field('can_pin_messages', required: true)]
    public private(set) bool $canPinMessages;

    /**
     * True, if the user is allowed to create forum topics
     */
    #[Field('can_manage_topics', required: true)]
    public private(set) bool $canManageTopics;

    /**
     * Date when restrictions will be lifted for this user; Unix time. If 0, then the user is restricted forever.
     */
    #[Field('until_date', required: true)]
    public private(set) int $untilDate;

}
