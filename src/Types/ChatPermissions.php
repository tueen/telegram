<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes actions that a non-administrator user is allowed to take in a chat.
 *
 * @link https://core.telegram.org/bots/api#chatpermissions
 */
class ChatPermissions extends Type
{
    /**
     * Optional. True, if the user is allowed to send text messages, rich messages, contacts, giveaways, giveaway winners, invoices, locations and venues
     */
    #[Field('can_send_messages', required: false)]
    private(set) ?bool $canSendMessages = null;

    /**
     * Optional. True, if the user is allowed to send audios
     */
    #[Field('can_send_audios', required: false)]
    private(set) ?bool $canSendAudios = null;

    /**
     * Optional. True, if the user is allowed to send documents
     */
    #[Field('can_send_documents', required: false)]
    private(set) ?bool $canSendDocuments = null;

    /**
     * Optional. True, if the user is allowed to send photos
     */
    #[Field('can_send_photos', required: false)]
    private(set) ?bool $canSendPhotos = null;

    /**
     * Optional. True, if the user is allowed to send videos
     */
    #[Field('can_send_videos', required: false)]
    private(set) ?bool $canSendVideos = null;

    /**
     * Optional. True, if the user is allowed to send video notes
     */
    #[Field('can_send_video_notes', required: false)]
    private(set) ?bool $canSendVideoNotes = null;

    /**
     * Optional. True, if the user is allowed to send voice notes
     */
    #[Field('can_send_voice_notes', required: false)]
    private(set) ?bool $canSendVoiceNotes = null;

    /**
     * Optional. True, if the user is allowed to send polls and checklists
     */
    #[Field('can_send_polls', required: false)]
    private(set) ?bool $canSendPolls = null;

    /**
     * Optional. True, if the user is allowed to send animations, games, stickers and use inline bots
     */
    #[Field('can_send_other_messages', required: false)]
    private(set) ?bool $canSendOtherMessages = null;

    /**
     * Optional. True, if the user is allowed to add web page previews to their messages
     */
    #[Field('can_add_web_page_previews', required: false)]
    private(set) ?bool $canAddWebPagePreviews = null;

    /**
     * Optional. True, if the user is allowed to react to messages. If omitted, defaults to the value of can_send_messages.
     */
    #[Field('can_react_to_messages', required: false)]
    private(set) ?bool $canReactToMessages = null;

    /**
     * Optional. True, if the user is allowed to edit their own tag. If omitted, defaults to the value of can_pin_messages.
     */
    #[Field('can_edit_tag', required: false)]
    private(set) ?bool $canEditTag = null;

    /**
     * Optional. True, if the user is allowed to change the chat title, photo and other settings. Ignored in public supergroups.
     */
    #[Field('can_change_info', required: false)]
    private(set) ?bool $canChangeInfo = null;

    /**
     * Optional. True, if the user is allowed to invite new users to the chat
     */
    #[Field('can_invite_users', required: false)]
    private(set) ?bool $canInviteUsers = null;

    /**
     * Optional. True, if the user is allowed to pin messages. Ignored in public supergroups.
     */
    #[Field('can_pin_messages', required: false)]
    private(set) ?bool $canPinMessages = null;

    /**
     * Optional. True, if the user is allowed to create forum topics. If omitted, defaults to the value of can_pin_messages.
     */
    #[Field('can_manage_topics', required: false)]
    private(set) ?bool $canManageTopics = null;

}
