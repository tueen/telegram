<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change settings of the bot. Settings that aren't specified remain unchanged. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmysettings
 */
#[ApiMethod('setMySettings', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetMySettings extends Method
{
    /**
     * Pass True to enable inline mode for the bot or False to disable it
     */
    #[Field('supports_inline_queries', required: false)]
    public ?bool $supportsInlineQueries = null;

    /**
     * New placeholder for inline queries, which is shown in the input field after the bot's username. Pass an empty string to use the default placeholder.
     */
    #[Field('inline_query_placeholder', required: false)]
    public ?string $inlineQueryPlaceholder = null;

    /**
     * Pass True to request the location of the user with every inline query to the bot or False to stop requesting it
     */
    #[Field('needs_inline_query_location', required: false)]
    public ?bool $needsInlineQueryLocation = null;

    /**
     * Denominator of the share of chosen inline results received by the bot; the bot receives 1 out of inline_feedback_share_denominator chosen results. Must be one of 1, 10, 100, or 1000. Pass 0 to stop receiving chosen inline results.
     */
    #[Field('inline_feedback_share_denominator', required: false)]
    public ?int $inlineFeedbackShareDenominator = null;

    /**
     * Pass True to allow adding the bot to groups or False to disallow it
     */
    #[Field('can_join_groups', required: false)]
    public ?bool $canJoinGroups = null;

    /**
     * Pass True to disable privacy mode for the bot or False to enable it
     */
    #[Field('can_read_all_group_messages', required: false)]
    public ?bool $canReadAllGroupMessages = null;

    /**
     * Pass True to allow connecting the bot to user accounts to manage them or False to disallow it
     */
    #[Field('can_connect_to_business', required: false)]
    public ?bool $canConnectToBusiness = null;

    /**
     * Pass True to allow creating other bots that will be controlled by the bot or False to disallow it
     */
    #[Field('can_manage_bots', required: false)]
    public ?bool $canManageBots = null;

    /**
     * Pass True to allow guest queries to the bot from chats it isn't a member of or False to disallow them
     */
    #[Field('supports_guest_queries', required: false)]
    public ?bool $supportsGuestQueries = null;

    /**
     * Pass True to allow assigning the bot to process join request queries or False to disallow it
     */
    #[Field('supports_join_request_queries', required: false)]
    public ?bool $supportsJoinRequestQueries = null;

    /**
     * Pass True to enable forum topic mode in private chats with the bot or False to disable it
     */
    #[Field('has_topics_enabled', required: false)]
    public ?bool $hasTopicsEnabled = null;

    /**
     * Pass True to allow users to create and delete topics in private chats with the bot or False to disallow it
     */
    #[Field('allows_users_to_create_topics', required: false)]
    public ?bool $allowsUsersToCreateTopics = null;

    /**
     * Pass True to show topics in private chats with the bot in a sidebar or False to stop showing them in a sidebar
     */
    #[Field('has_topic_sidebar', required: false)]
    public ?bool $hasTopicSidebar = null;

    /**
     * Pass True to allow the bot to communicate with other bots in groups, private chats, and through business accounts or False to disallow it
     */
    #[Field('can_communicate_with_bots', required: false)]
    public ?bool $canCommunicateWithBots = null;

    /**
     * Mode in which the bot receives callback queries from buttons in messages sent by the bot. Must be one of "default" (the field message is always used and contains an InaccessibleMessage if the message is inaccessible to the bot), "auto" (the field inline_message_id is used instead of the field message if the message is inaccessible to the bot; the field message can still contain an inaccessible message if the message has just been deleted), or "inline" (the field inline_message_id is always used instead of the field message).
     */
    #[Field('callback_query_mode', required: false)]
    public ?string $callbackQueryMode = null;

    public function __construct(
        ?bool $supportsInlineQueries = null,
        ?string $inlineQueryPlaceholder = null,
        ?bool $needsInlineQueryLocation = null,
        ?int $inlineFeedbackShareDenominator = null,
        ?bool $canJoinGroups = null,
        ?bool $canReadAllGroupMessages = null,
        ?bool $canConnectToBusiness = null,
        ?bool $canManageBots = null,
        ?bool $supportsGuestQueries = null,
        ?bool $supportsJoinRequestQueries = null,
        ?bool $hasTopicsEnabled = null,
        ?bool $allowsUsersToCreateTopics = null,
        ?bool $hasTopicSidebar = null,
        ?bool $canCommunicateWithBots = null,
        ?string $callbackQueryMode = null,
        mixed ...$extra
    )
    {
        if ($supportsInlineQueries !== null) $this->supportsInlineQueries = $supportsInlineQueries;
        if ($inlineQueryPlaceholder !== null) $this->inlineQueryPlaceholder = $inlineQueryPlaceholder;
        if ($needsInlineQueryLocation !== null) $this->needsInlineQueryLocation = $needsInlineQueryLocation;
        if ($inlineFeedbackShareDenominator !== null) $this->inlineFeedbackShareDenominator = $inlineFeedbackShareDenominator;
        if ($canJoinGroups !== null) $this->canJoinGroups = $canJoinGroups;
        if ($canReadAllGroupMessages !== null) $this->canReadAllGroupMessages = $canReadAllGroupMessages;
        if ($canConnectToBusiness !== null) $this->canConnectToBusiness = $canConnectToBusiness;
        if ($canManageBots !== null) $this->canManageBots = $canManageBots;
        if ($supportsGuestQueries !== null) $this->supportsGuestQueries = $supportsGuestQueries;
        if ($supportsJoinRequestQueries !== null) $this->supportsJoinRequestQueries = $supportsJoinRequestQueries;
        if ($hasTopicsEnabled !== null) $this->hasTopicsEnabled = $hasTopicsEnabled;
        if ($allowsUsersToCreateTopics !== null) $this->allowsUsersToCreateTopics = $allowsUsersToCreateTopics;
        if ($hasTopicSidebar !== null) $this->hasTopicSidebar = $hasTopicSidebar;
        if ($canCommunicateWithBots !== null) $this->canCommunicateWithBots = $canCommunicateWithBots;
        if ($callbackQueryMode !== null) $this->callbackQueryMode = $callbackQueryMode;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
