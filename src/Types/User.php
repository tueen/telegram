<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Concerns\HasUserHelpers;

/**
 * This object represents a Telegram user or bot.
 *
 * @link https://core.telegram.org/bots/api#user
 */
class User extends Type
{
    use HasUserHelpers;

    /**
     * Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('id', required: true)]
    public private(set) int $id;

    /**
     * True, if this user is a bot
     */
    #[Field('is_bot', required: true)]
    public private(set) bool $isBot;

    /**
     * User's or bot's first name
     */
    #[Field('first_name', required: true)]
    public private(set) string $firstName;

    /**
     * Optional. User's or bot's last name
     */
    #[Field('last_name', required: false)]
    public private(set) ?string $lastName = null;

    /**
     * Optional. User's or bot's username
     */
    #[Field('username', required: false)]
    public private(set) ?string $username = null;

    /**
     * Optional. IETF language tag of the user's language
     */
    #[Field('language_code', required: false)]
    public private(set) ?string $languageCode = null;

    /**
     * Optional. True, if this user is a Telegram Premium user
     */
    #[Field('is_premium', required: false)]
    public private(set) ?bool $isPremium = null;

    /**
     * Optional. True, if this user added the bot to the attachment menu
     */
    #[Field('added_to_attachment_menu', required: false)]
    public private(set) ?bool $addedToAttachmentMenu = null;

    /**
     * Optional. True, if the bot can be invited to groups. Returned only in getMe.
     */
    #[Field('can_join_groups', required: false)]
    public private(set) ?bool $canJoinGroups = null;

    /**
     * Optional. True, if privacy mode is disabled for the bot. Returned only in getMe.
     */
    #[Field('can_read_all_group_messages', required: false)]
    public private(set) ?bool $canReadAllGroupMessages = null;

    /**
     * Optional. True, if the bot supports guest queries from chats it is not a member of. Returned only in getMe.
     */
    #[Field('supports_guest_queries', required: false)]
    public private(set) ?bool $supportsGuestQueries = null;

    /**
     * Optional. True, if the bot supports inline queries. Returned only in getMe.
     */
    #[Field('supports_inline_queries', required: false)]
    public private(set) ?bool $supportsInlineQueries = null;

    /**
     * Optional. True, if the bot can be connected to a user account to manage it. Returned only in getMe.
     */
    #[Field('can_connect_to_business', required: false)]
    public private(set) ?bool $canConnectToBusiness = null;

    /**
     * Optional. True, if the bot has a main Web App. Returned only in getMe.
     */
    #[Field('has_main_web_app', required: false)]
    public private(set) ?bool $hasMainWebApp = null;

    /**
     * Optional. True, if the bot has forum topic mode enabled in private chats. Returned only in getMe.
     */
    #[Field('has_topics_enabled', required: false)]
    public private(set) ?bool $hasTopicsEnabled = null;

    /**
     * Optional. True, if the bot allows users to create and delete topics in private chats. Returned only in getMe.
     */
    #[Field('allows_users_to_create_topics', required: false)]
    public private(set) ?bool $allowsUsersToCreateTopics = null;

    /**
     * Optional. True, if other bots can be created to be controlled by the bot. Returned only in getMe.
     */
    #[Field('can_manage_bots', required: false)]
    public private(set) ?bool $canManageBots = null;

    /**
     * Optional. True, if the bot supports join request queries and can be assigned to process them. Returned only in getMe.
     */
    #[Field('supports_join_request_queries', required: false)]
    public private(set) ?bool $supportsJoinRequestQueries = null;

}
