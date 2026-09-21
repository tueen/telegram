<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\BusinessConnection;
use Tueen\Telegram\Types\BusinessMessagesDeleted;
use Tueen\Telegram\Types\MessageReactionUpdated;
use Tueen\Telegram\Types\MessageReactionCountUpdated;
use Tueen\Telegram\Types\InlineQuery;
use Tueen\Telegram\Types\ChosenInlineResult;
use Tueen\Telegram\Types\CallbackQuery;
use Tueen\Telegram\Types\ShippingQuery;
use Tueen\Telegram\Types\PreCheckoutQuery;
use Tueen\Telegram\Types\PaidMediaPurchased;
use Tueen\Telegram\Types\Poll;
use Tueen\Telegram\Types\PollAnswer;
use Tueen\Telegram\Types\ChatMemberUpdated;
use Tueen\Telegram\Types\ChatJoinRequest;
use Tueen\Telegram\Types\ChatBoostUpdated;
use Tueen\Telegram\Types\ChatBoostRemoved;
use Tueen\Telegram\Types\ManagedBotUpdated;
use Tueen\Telegram\Types\BotSubscriptionUpdated;
use Tueen\Telegram\Types\MessageGenerationStopped;
use Tueen\Telegram\Types\Concerns\HasUpdateHelpers;

/**
 * This object represents an incoming update.
 * At most one of the optional fields can be present in any given update.
 *
 * @link https://core.telegram.org/bots/api#update
 */
class Update extends Type
{
    use HasUpdateHelpers;

    /**
     * The update's unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you're using webhooks, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
     */
    #[Field('update_id', required: true)]
    public private(set) int $updateId;

    /**
     * Optional. New incoming message of any kind - text, photo, sticker, etc.
     */
    #[Field('message', required: false)]
    public private(set) ?Message $message = null;

    /**
     * Optional. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
     */
    #[Field('edited_message', required: false)]
    public private(set) ?Message $editedMessage = null;

    /**
     * Optional. New incoming channel post of any kind - text, photo, sticker, etc.
     */
    #[Field('channel_post', required: false)]
    public private(set) ?Message $channelPost = null;

    /**
     * Optional. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
     */
    #[Field('edited_channel_post', required: false)]
    public private(set) ?Message $editedChannelPost = null;

    /**
     * Optional. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
     */
    #[Field('business_connection', required: false)]
    public private(set) ?BusinessConnection $businessConnection = null;

    /**
     * Optional. New message from a connected business account
     */
    #[Field('business_message', required: false)]
    public private(set) ?Message $businessMessage = null;

    /**
     * Optional. New version of a message from a connected business account
     */
    #[Field('edited_business_message', required: false)]
    public private(set) ?Message $editedBusinessMessage = null;

    /**
     * Optional. Messages were deleted from a connected business account
     */
    #[Field('deleted_business_messages', required: false)]
    public private(set) ?BusinessMessagesDeleted $deletedBusinessMessages = null;

    /**
     * Optional. New guest message. The bot can use the field Message.guest_query_id and the method answerGuestQuery to send a message in response.
     */
    #[Field('guest_message', required: false)]
    public private(set) ?Message $guestMessage = null;

    /**
     * Optional. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify "message_reaction" in the list of allowed_updates to receive these updates. The update isn't received for reactions set by bots.
     */
    #[Field('message_reaction', required: false)]
    public private(set) ?MessageReactionUpdated $messageReaction = null;

    /**
     * Optional. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify "message_reaction_count" in the list of allowed_updates to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
     */
    #[Field('message_reaction_count', required: false)]
    public private(set) ?MessageReactionCountUpdated $messageReactionCount = null;

    /**
     * Optional. New incoming inline query
     */
    #[Field('inline_query', required: false)]
    public private(set) ?InlineQuery $inlineQuery = null;

    /**
     * Optional. The result of an inline query that was chosen by a user and sent to their chat partner. Please see our documentation on the feedback collecting for details on how to enable these updates for your bot.
     */
    #[Field('chosen_inline_result', required: false)]
    public private(set) ?ChosenInlineResult $chosenInlineResult = null;

    /**
     * Optional. New incoming callback query
     */
    #[Field('callback_query', required: false)]
    public private(set) ?CallbackQuery $callbackQuery = null;

    /**
     * Optional. New incoming shipping query. Only for invoices with flexible price.
     */
    #[Field('shipping_query', required: false)]
    public private(set) ?ShippingQuery $shippingQuery = null;

    /**
     * Optional. New incoming pre-checkout query. Contains full information about checkout.
     */
    #[Field('pre_checkout_query', required: false)]
    public private(set) ?PreCheckoutQuery $preCheckoutQuery = null;

    /**
     * Optional. A user purchased paid media with a non-empty payload sent by the bot in a non-channel chat
     */
    #[Field('purchased_paid_media', required: false)]
    public private(set) ?PaidMediaPurchased $purchasedPaidMedia = null;

    /**
     * Optional. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot.
     */
    #[Field('poll', required: false)]
    public private(set) ?Poll $poll = null;

    /**
     * Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
     */
    #[Field('poll_answer', required: false)]
    public private(set) ?PollAnswer $pollAnswer = null;

    /**
     * Optional. The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
     */
    #[Field('my_chat_member', required: false)]
    public private(set) ?ChatMemberUpdated $myChatMember = null;

    /**
     * Optional. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify "chat_member" in the list of allowed_updates to receive these updates.
     */
    #[Field('chat_member', required: false)]
    public private(set) ?ChatMemberUpdated $chatMember = null;

    /**
     * Optional. A request to join the chat has been sent. The bot must have the can_invite_users administrator right in the chat to receive these updates.
     */
    #[Field('chat_join_request', required: false)]
    public private(set) ?ChatJoinRequest $chatJoinRequest = null;

    /**
     * Optional. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
     */
    #[Field('chat_boost', required: false)]
    public private(set) ?ChatBoostUpdated $chatBoost = null;

    /**
     * Optional. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
     */
    #[Field('removed_chat_boost', required: false)]
    public private(set) ?ChatBoostRemoved $removedChatBoost = null;

    /**
     * Optional. A new bot was created to be managed by the bot, or token or owner of a managed bot was changed
     */
    #[Field('managed_bot', required: false)]
    public private(set) ?ManagedBotUpdated $managedBot = null;

    /**
     * Optional. User payment subscription has changed
     */
    #[Field('subscription', required: false)]
    public private(set) ?BotSubscriptionUpdated $subscription = null;

    /**
     * Optional. A user asked the bot to stop the generation of a message
     */
    #[Field('stopped_message_generation', required: false)]
    public private(set) ?MessageGenerationStopped $stoppedMessageGeneration = null;

}
