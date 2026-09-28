<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents the rights of a business bot.
 *
 * @link https://core.telegram.org/bots/api#businessbotrights
 */
class BusinessBotRights extends Type
{
    /**
     * Optional. True, if the bot can send and edit messages in the private chats that had incoming messages in the last 24 hours
     */
    #[Field('can_reply', required: false)]
    private(set) ?bool $canReply = null;

    /**
     * Optional. True, if the bot can mark incoming private messages as read
     */
    #[Field('can_read_messages', required: false)]
    private(set) ?bool $canReadMessages = null;

    /**
     * Optional. True, if the bot can delete messages sent by the bot
     */
    #[Field('can_delete_sent_messages', required: false)]
    private(set) ?bool $canDeleteSentMessages = null;

    /**
     * Optional. True, if the bot can delete all private messages in managed chats
     */
    #[Field('can_delete_all_messages', required: false)]
    private(set) ?bool $canDeleteAllMessages = null;

    /**
     * Optional. True, if the bot can edit the first and last name of the business account
     */
    #[Field('can_edit_name', required: false)]
    private(set) ?bool $canEditName = null;

    /**
     * Optional. True, if the bot can edit the bio of the business account
     */
    #[Field('can_edit_bio', required: false)]
    private(set) ?bool $canEditBio = null;

    /**
     * Optional. True, if the bot can edit the profile photo of the business account
     */
    #[Field('can_edit_profile_photo', required: false)]
    private(set) ?bool $canEditProfilePhoto = null;

    /**
     * Optional. True, if the bot can edit the username of the business account
     */
    #[Field('can_edit_username', required: false)]
    private(set) ?bool $canEditUsername = null;

    /**
     * Optional. True, if the bot can change the privacy settings pertaining to gifts for the business account
     */
    #[Field('can_change_gift_settings', required: false)]
    private(set) ?bool $canChangeGiftSettings = null;

    /**
     * Optional. True, if the bot can view gifts and the amount of Telegram Stars owned by the business account
     */
    #[Field('can_view_gifts_and_stars', required: false)]
    private(set) ?bool $canViewGiftsAndStars = null;

    /**
     * Optional. True, if the bot can convert regular gifts owned by the business account to Telegram Stars
     */
    #[Field('can_convert_gifts_to_stars', required: false)]
    private(set) ?bool $canConvertGiftsToStars = null;

    /**
     * Optional. True, if the bot can transfer and upgrade gifts owned by the business account
     */
    #[Field('can_transfer_and_upgrade_gifts', required: false)]
    private(set) ?bool $canTransferAndUpgradeGifts = null;

    /**
     * Optional. True, if the bot can transfer Telegram Stars received by the business account to its own account, or use them to upgrade and transfer gifts
     */
    #[Field('can_transfer_stars', required: false)]
    private(set) ?bool $canTransferStars = null;

    /**
     * Optional. True, if the bot can post, edit and delete stories on behalf of the business account
     */
    #[Field('can_manage_stories', required: false)]
    private(set) ?bool $canManageStories = null;

}
