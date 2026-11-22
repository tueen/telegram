<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * This object contains full information about the bot itself.
 *
 * @link https://core.telegram.org/bots/api#botfullinfo
 */
class BotFullInfo extends Type
{
    /**
     * Unique identifier for this bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('id', required: true)]
    private(set) ?int $id = null;

    /**
     * Always True
     */
    #[Field('is_bot', required: true)]
    private(set) ?bool $isBot = null;

    /**
     * Bot's first name
     */
    #[Field('first_name', required: true)]
    private(set) ?string $firstName = null;

    /**
     * Optional. Bot's username
     */
    #[Field('username', required: false)]
    private(set) ?string $username = null;

    /**
     * Optional. If non-empty, the list of all active usernames of the bot
     * @var String[]|null
     */
    #[Field('active_usernames', required: false)]
    private(set) ?array $activeUsernames = null;

    /**
     * True, if the bot can be added to groups
     */
    #[Field('can_join_groups', required: true)]
    private(set) ?bool $canJoinGroups = null;

    /**
     * True, if privacy mode is disabled for the bot
     */
    #[Field('can_read_all_group_messages', required: true)]
    private(set) ?bool $canReadAllGroupMessages = null;

    /**
     * True, if the bot supports guest queries from chats it is not a member of
     */
    #[Field('supports_guest_queries', required: true)]
    private(set) ?bool $supportsGuestQueries = null;

    /**
     * True, if the bot supports inline queries
     */
    #[Field('supports_inline_queries', required: true)]
    private(set) ?bool $supportsInlineQueries = null;

    /**
     * Optional. Placeholder for inline queries, which is shown in the input field after the bot's username; omitted if the default placeholder is used
     */
    #[Field('inline_query_placeholder', required: false)]
    private(set) ?string $inlineQueryPlaceholder = null;

    /**
     * True, if the bot requests the location of the user with every inline query
     */
    #[Field('needs_inline_query_location', required: true)]
    private(set) ?bool $needsInlineQueryLocation = null;

    /**
     * True, if the bot can be connected to a user account to manage it
     */
    #[Field('can_connect_to_business', required: true)]
    private(set) ?bool $canConnectToBusiness = null;

    /**
     * True, if the bot has a main Web App
     */
    #[Field('has_main_web_app', required: true)]
    private(set) ?bool $hasMainWebApp = null;

    /**
     * True, if the bot has forum topic mode enabled in private chats
     */
    #[Field('has_topics_enabled', required: true)]
    private(set) ?bool $hasTopicsEnabled = null;

    /**
     * True, if the bot allows users to create and delete topics in private chats
     */
    #[Field('allows_users_to_create_topics', required: true)]
    private(set) ?bool $allowsUsersToCreateTopics = null;

    /**
     * True, if topics in private chats with the bot are shown in a sidebar
     */
    #[Field('has_topic_sidebar', required: true)]
    private(set) ?bool $hasTopicSidebar = null;

    /**
     * True, if other bots can be created to be controlled by the bot
     */
    #[Field('can_manage_bots', required: true)]
    private(set) ?bool $canManageBots = null;

    /**
     * True, if the bot supports join request queries and can be assigned to process them
     */
    #[Field('supports_join_request_queries', required: true)]
    private(set) ?bool $supportsJoinRequestQueries = null;

}
