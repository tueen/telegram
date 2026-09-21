<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\DirectMessagesTopic;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\MessageOrigin;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\ExternalReplyInfo;
use Tueen\Telegram\Types\TextQuote;
use Tueen\Telegram\Types\Story;
use Tueen\Telegram\Types\MessageEntity;
use Tueen\Telegram\Types\LinkPreviewOptions;
use Tueen\Telegram\Types\SuggestedPostInfo;
use Tueen\Telegram\Types\RichMessage;
use Tueen\Telegram\Types\Animation;
use Tueen\Telegram\Types\Audio;
use Tueen\Telegram\Types\Document;
use Tueen\Telegram\Types\LivePhoto;
use Tueen\Telegram\Types\PaidMediaInfo;
use Tueen\Telegram\Types\PhotoSize;
use Tueen\Telegram\Types\Sticker;
use Tueen\Telegram\Types\Video;
use Tueen\Telegram\Types\VideoNote;
use Tueen\Telegram\Types\Voice;
use Tueen\Telegram\Types\Checklist;
use Tueen\Telegram\Types\Contact;
use Tueen\Telegram\Types\Dice;
use Tueen\Telegram\Types\Game;
use Tueen\Telegram\Types\Poll;
use Tueen\Telegram\Types\Venue;
use Tueen\Telegram\Types\Location;
use Tueen\Telegram\Types\ChatOwnerLeft;
use Tueen\Telegram\Types\ChatOwnerChanged;
use Tueen\Telegram\Types\MessageAutoDeleteTimerChanged;
use Tueen\Telegram\Types\MaybeInaccessibleMessage;
use Tueen\Telegram\Types\Invoice;
use Tueen\Telegram\Types\SuccessfulPayment;
use Tueen\Telegram\Types\RefundedPayment;
use Tueen\Telegram\Types\UsersShared;
use Tueen\Telegram\Types\ChatShared;
use Tueen\Telegram\Types\GiftInfo;
use Tueen\Telegram\Types\UniqueGiftInfo;
use Tueen\Telegram\Types\WriteAccessAllowed;
use Tueen\Telegram\Types\PassportData;
use Tueen\Telegram\Types\ProximityAlertTriggered;
use Tueen\Telegram\Types\ChatBoostAdded;
use Tueen\Telegram\Types\ChatBackground;
use Tueen\Telegram\Types\ChecklistTasksDone;
use Tueen\Telegram\Types\ChecklistTasksAdded;
use Tueen\Telegram\Types\CommunityChatAdded;
use Tueen\Telegram\Types\CommunityChatJoined;
use Tueen\Telegram\Types\CommunityChatRemoved;
use Tueen\Telegram\Types\DirectMessagePriceChanged;
use Tueen\Telegram\Types\ForumTopicCreated;
use Tueen\Telegram\Types\ForumTopicEdited;
use Tueen\Telegram\Types\ForumTopicClosed;
use Tueen\Telegram\Types\ForumTopicReopened;
use Tueen\Telegram\Types\GeneralForumTopicHidden;
use Tueen\Telegram\Types\GeneralForumTopicUnhidden;
use Tueen\Telegram\Types\GiveawayCreated;
use Tueen\Telegram\Types\Giveaway;
use Tueen\Telegram\Types\GiveawayWinners;
use Tueen\Telegram\Types\GiveawayCompleted;
use Tueen\Telegram\Types\ManagedBotCreated;
use Tueen\Telegram\Types\PaidMessagePriceChanged;
use Tueen\Telegram\Types\PollOptionAdded;
use Tueen\Telegram\Types\PollOptionDeleted;
use Tueen\Telegram\Types\SuggestedPostApproved;
use Tueen\Telegram\Types\SuggestedPostApprovalFailed;
use Tueen\Telegram\Types\SuggestedPostDeclined;
use Tueen\Telegram\Types\SuggestedPostPaid;
use Tueen\Telegram\Types\SuggestedPostRefunded;
use Tueen\Telegram\Types\VideoChatScheduled;
use Tueen\Telegram\Types\VideoChatStarted;
use Tueen\Telegram\Types\VideoChatEnded;
use Tueen\Telegram\Types\VideoChatParticipantsInvited;
use Tueen\Telegram\Types\WebAppData;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * This object represents a message.
 *
 * @link https://core.telegram.org/bots/api#message
 */
class Message extends MaybeInaccessibleMessage
{
    /**
     * Unique message identifier inside this chat; 0 for ephemeral messages. In specific instances (e.g., a message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent.
     */
    #[Field('message_id', required: true)]
    public private(set) int $messageId;

    /**
     * Optional. Unique identifier of a message thread or forum topic to which the message belongs; for supergroups and private chats only
     */
    #[Field('message_thread_id', required: false)]
    public private(set) ?int $messageThreadId = null;

    /**
     * Optional. Information about the direct messages chat topic that contains the message
     */
    #[Field('direct_messages_topic', required: false)]
    public private(set) ?DirectMessagesTopic $directMessagesTopic = null;

    /**
     * Optional. Sender of the message; may be empty for messages sent to channels. For backward compatibility, if the message was sent on behalf of a chat, the field contains a fake sender user in non-channel chats.
     */
    #[Field('from', required: false)]
    public private(set) ?User $from = null;

    /**
     * Optional. Sender of the message when sent on behalf of a chat. For example, the supergroup itself for messages sent by its anonymous administrators or a linked channel for messages automatically forwarded to the channel's discussion group. For backward compatibility, if the message was sent on behalf of a chat, the field from contains a fake sender user in non-channel chats.
     */
    #[Field('sender_chat', required: false)]
    public private(set) ?Chat $senderChat = null;

    /**
     * Optional. If the sender of the message boosted the chat, the number of boosts added by the user
     */
    #[Field('sender_boost_count', required: false)]
    public private(set) ?int $senderBoostCount = null;

    /**
     * Optional. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
     */
    #[Field('sender_business_bot', required: false)]
    public private(set) ?User $senderBusinessBot = null;

    /**
     * Optional. Tag or custom title of the sender of the message; for supergroups only
     */
    #[Field('sender_tag', required: false)]
    public private(set) ?string $senderTag = null;

    /**
     * Optional. For ephemeral messages, the user who received the message
     */
    #[Field('receiver_user', required: false)]
    public private(set) ?User $receiverUser = null;

    /**
     * Optional. For ephemeral messages, identifier of the ephemeral message inside this chat. The identifier may be reused for another ephemeral message after the message is deleted or expires.
     */
    #[Field('ephemeral_message_id', required: false)]
    public private(set) ?int $ephemeralMessageId = null;

    /**
     * Date the message was sent in Unix time. It is always a positive number, representing a valid date.
     */
    #[Field('date', required: true)]
    public private(set) int $date;

    /**
     * Optional. The unique identifier for the guest query. Use this identifier with the method answerGuestQuery to send a response message. If non-empty, the message belongs to the chat where the guest bot was summoned, which may not coincide with other existing bot chats sharing the same identifier.
     */
    #[Field('guest_query_id', required: false)]
    public private(set) ?string $guestQueryId = null;

    /**
     * Optional. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
     */
    #[Field('business_connection_id', required: false)]
    public private(set) ?string $businessConnectionId = null;

    /**
     * Chat the message belongs to
     */
    #[Field('chat', required: true)]
    public private(set) Chat $chat;

    /**
     * Optional. Information about the original message for forwarded messages
     */
    #[Field('forward_origin', required: false)]
    public private(set) ?MessageOrigin $forwardOrigin = null;

    /**
     * Optional. True, if the message is sent to a topic in a forum supergroup or a private chat with the bot
     */
    #[Field('is_topic_message', required: false)]
    public private(set) ?bool $isTopicMessage = null;

    /**
     * Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion group
     */
    #[Field('is_automatic_forward', required: false)]
    public private(set) ?bool $isAutomaticForward = null;

    /**
     * Optional. For replies in the same chat and message thread, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply. If the message is a reply to an ephemeral message, then this field may be omitted.
     */
    #[Field('reply_to_message', required: false)]
    public private(set) ?Message $replyToMessage = null;

    /**
     * Optional. Information about the message that is being replied to, which may come from another chat or forum topic
     */
    #[Field('external_reply', required: false)]
    public private(set) ?ExternalReplyInfo $externalReply = null;

    /**
     * Optional. For replies that quote part of the original message, the quoted part of the message
     */
    #[Field('quote', required: false)]
    public private(set) ?TextQuote $quote = null;

    /**
     * Optional. For replies to a story, the original story
     */
    #[Field('reply_to_story', required: false)]
    public private(set) ?Story $replyToStory = null;

    /**
     * Optional. Identifier of the specific checklist task that is being replied to
     */
    #[Field('reply_to_checklist_task_id', required: false)]
    public private(set) ?int $replyToChecklistTaskId = null;

    /**
     * Optional. Persistent identifier of the specific poll option that is being replied to
     */
    #[Field('reply_to_poll_option_id', required: false)]
    public private(set) ?string $replyToPollOptionId = null;

    /**
     * Optional. Bot through which the message was sent
     */
    #[Field('via_bot', required: false)]
    public private(set) ?User $viaBot = null;

    /**
     * Optional. For a message sent by a guest bot, this is the user whose original message triggered the bot's response
     */
    #[Field('guest_bot_caller_user', required: false)]
    public private(set) ?User $guestBotCallerUser = null;

    /**
     * Optional. For a message sent by a guest bot, this is the chat whose original message triggered the bot's response
     */
    #[Field('guest_bot_caller_chat', required: false)]
    public private(set) ?Chat $guestBotCallerChat = null;

    /**
     * Optional. Date the message was last edited in Unix time
     */
    #[Field('edit_date', required: false)]
    public private(set) ?int $editDate = null;

    /**
     * Optional. True, if the message can't be forwarded
     */
    #[Field('has_protected_content', required: false)]
    public private(set) ?bool $hasProtectedContent = null;

    /**
     * Optional. True, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
     */
    #[Field('is_from_offline', required: false)]
    public private(set) ?bool $isFromOffline = null;

    /**
     * Optional. True, if the message is a paid post. Note that such posts must not be deleted for 24 hours to receive the payment and can't be edited.
     */
    #[Field('is_paid_post', required: false)]
    public private(set) ?bool $isPaidPost = null;

    /**
     * Optional. The unique identifier inside this chat of a media message group this message belongs to
     */
    #[Field('media_group_id', required: false)]
    public private(set) ?string $mediaGroupId = null;

    /**
     * Optional. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
     */
    #[Field('author_signature', required: false)]
    public private(set) ?string $authorSignature = null;

    /**
     * Optional. The number of Telegram Stars that were paid by the sender of the message to send it
     */
    #[Field('paid_star_count', required: false)]
    public private(set) ?int $paidStarCount = null;

    /**
     * Optional. For text messages, the actual UTF-8 text of the message
     */
    #[Field('text', required: false)]
    public private(set) ?string $text = null;

    /**
     * Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     * @var MessageEntity[]|null
     */
    #[Field('entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $entities = null;

    /**
     * Optional. Options used for link preview generation for the message, if it is a text message and link preview options were changed
     */
    #[Field('link_preview_options', required: false)]
    public private(set) ?LinkPreviewOptions $linkPreviewOptions = null;

    /**
     * Optional. Information about suggested post parameters if the message is a suggested post in a channel direct messages chat. If the message is an approved or declined suggested post, then it can't be edited.
     */
    #[Field('suggested_post_info', required: false)]
    public private(set) ?SuggestedPostInfo $suggestedPostInfo = null;

    /**
     * Optional. Unique identifier of the message effect added to the message
     */
    #[Field('effect_id', required: false)]
    public private(set) ?string $effectId = null;

    /**
     * Optional. Message is a rich formatted message
     */
    #[Field('rich_message', required: false)]
    public private(set) ?RichMessage $richMessage = null;

    /**
     * Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set.
     */
    #[Field('animation', required: false)]
    public private(set) ?Animation $animation = null;

    /**
     * Optional. Message is an audio file, information about the file
     */
    #[Field('audio', required: false)]
    public private(set) ?Audio $audio = null;

    /**
     * Optional. Message is a general file, information about the file
     */
    #[Field('document', required: false)]
    public private(set) ?Document $document = null;

    /**
     * Optional. Message is a live photo, information about the live photo. For backward compatibility, when this field is set, the photo field will also be set.
     */
    #[Field('live_photo', required: false)]
    public private(set) ?LivePhoto $livePhoto = null;

    /**
     * Optional. Message contains paid media; information about the paid media
     */
    #[Field('paid_media', required: false)]
    public private(set) ?PaidMediaInfo $paidMedia = null;

    /**
     * Optional. Message is a photo, available sizes of the photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) ?array $photo = null;

    /**
     * Optional. Message is a sticker, information about the sticker
     */
    #[Field('sticker', required: false)]
    public private(set) ?Sticker $sticker = null;

    /**
     * Optional. Message is a forwarded story
     */
    #[Field('story', required: false)]
    public private(set) ?Story $story = null;

    /**
     * Optional. Message is a video, information about the video
     */
    #[Field('video', required: false)]
    public private(set) ?Video $video = null;

    /**
     * Optional. Message is a video note, information about the video message
     */
    #[Field('video_note', required: false)]
    public private(set) ?VideoNote $videoNote = null;

    /**
     * Optional. Message is a voice message, information about the file
     */
    #[Field('voice', required: false)]
    public private(set) ?Voice $voice = null;

    /**
     * Optional. Caption for the animation, audio, document, paid media, photo, video or voice
     */
    #[Field('caption', required: false)]
    public private(set) ?string $caption = null;

    /**
     * Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
     * @var MessageEntity[]|null
     */
    #[Field('caption_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $captionEntities = null;

    /**
     * Optional. True, if the caption must be shown above the message media
     */
    #[Field('show_caption_above_media', required: false)]
    public private(set) ?bool $showCaptionAboveMedia = null;

    /**
     * Optional. True, if the message media is covered by a spoiler animation
     */
    #[Field('has_media_spoiler', required: false)]
    public private(set) ?bool $hasMediaSpoiler = null;

    /**
     * Optional. Message is a checklist
     */
    #[Field('checklist', required: false)]
    public private(set) ?Checklist $checklist = null;

    /**
     * Optional. Message is a shared contact, information about the contact
     */
    #[Field('contact', required: false)]
    public private(set) ?Contact $contact = null;

    /**
     * Optional. Message is a dice with random value
     */
    #[Field('dice', required: false)]
    public private(set) ?Dice $dice = null;

    /**
     * Optional. Message is a game, information about the game. More about games: https://core.telegram.org/bots/api#games
     */
    #[Field('game', required: false)]
    public private(set) ?Game $game = null;

    /**
     * Optional. Message is a native poll, information about the poll
     */
    #[Field('poll', required: false)]
    public private(set) ?Poll $poll = null;

    /**
     * Optional. Message is a venue, information about the venue. For backward compatibility, when this field is set, the location field will also be set.
     */
    #[Field('venue', required: false)]
    public private(set) ?Venue $venue = null;

    /**
     * Optional. Message is a shared location, information about the location
     */
    #[Field('location', required: false)]
    public private(set) ?Location $location = null;

    /**
     * Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
     * @var User[]|null
     */
    #[Field('new_chat_members', required: false)]
    #[ArrayOf(User::class)]
    public private(set) ?array $newChatMembers = null;

    /**
     * Optional. A member was removed from the group, information about them (this member may be the bot itself)
     */
    #[Field('left_chat_member', required: false)]
    public private(set) ?User $leftChatMember = null;

    /**
     * Optional. Service message: chat owner has left
     */
    #[Field('chat_owner_left', required: false)]
    public private(set) ?ChatOwnerLeft $chatOwnerLeft = null;

    /**
     * Optional. Service message: chat owner has changed
     */
    #[Field('chat_owner_changed', required: false)]
    public private(set) ?ChatOwnerChanged $chatOwnerChanged = null;

    /**
     * Optional. A chat title was changed to this value
     */
    #[Field('new_chat_title', required: false)]
    public private(set) ?string $newChatTitle = null;

    /**
     * Optional. A chat photo was change to this value
     * @var PhotoSize[]|null
     */
    #[Field('new_chat_photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) ?array $newChatPhoto = null;

    /**
     * Optional. Service message: the chat photo was deleted
     */
    #[Field('delete_chat_photo', required: false)]
    public private(set) ?bool $deleteChatPhoto = null;

    /**
     * Optional. Service message: the group has been created
     */
    #[Field('group_chat_created', required: false)]
    public private(set) ?bool $groupChatCreated = null;

    /**
     * Optional. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can't be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
     */
    #[Field('supergroup_chat_created', required: false)]
    public private(set) ?bool $supergroupChatCreated = null;

    /**
     * Optional. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can't be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
     */
    #[Field('channel_chat_created', required: false)]
    public private(set) ?bool $channelChatCreated = null;

    /**
     * Optional. Service message: auto-delete timer settings changed in the chat
     */
    #[Field('message_auto_delete_timer_changed', required: false)]
    public private(set) ?MessageAutoDeleteTimerChanged $messageAutoDeleteTimerChanged = null;

    /**
     * Optional. The group has been migrated to a supergroup with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('migrate_to_chat_id', required: false)]
    public private(set) ?int $migrateToChatId = null;

    /**
     * Optional. The supergroup has been migrated from a group with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('migrate_from_chat_id', required: false)]
    public private(set) ?int $migrateFromChatId = null;

    /**
     * Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
     */
    #[Field('pinned_message', required: false)]
    public private(set) ?MaybeInaccessibleMessage $pinnedMessage = null;

    /**
     * Optional. Message is an invoice for a payment, information about the invoice. More about payments: https://core.telegram.org/bots/api#payments
     */
    #[Field('invoice', required: false)]
    public private(set) ?Invoice $invoice = null;

    /**
     * Optional. Message is a service message about a successful payment, information about the payment. More about payments: https://core.telegram.org/bots/api#payments
     */
    #[Field('successful_payment', required: false)]
    public private(set) ?SuccessfulPayment $successfulPayment = null;

    /**
     * Optional. Message is a service message about a refunded payment, information about the payment. More about payments: https://core.telegram.org/bots/api#payments
     */
    #[Field('refunded_payment', required: false)]
    public private(set) ?RefundedPayment $refundedPayment = null;

    /**
     * Optional. Service message: users were shared with the bot
     */
    #[Field('users_shared', required: false)]
    public private(set) ?UsersShared $usersShared = null;

    /**
     * Optional. Service message: a chat was shared with the bot
     */
    #[Field('chat_shared', required: false)]
    public private(set) ?ChatShared $chatShared = null;

    /**
     * Optional. Service message: a regular gift was sent or received
     */
    #[Field('gift', required: false)]
    public private(set) ?GiftInfo $gift = null;

    /**
     * Optional. Service message: a unique gift was sent or received
     */
    #[Field('unique_gift', required: false)]
    public private(set) ?UniqueGiftInfo $uniqueGift = null;

    /**
     * Optional. Service message: upgrade of a gift was purchased after the gift was sent
     */
    #[Field('gift_upgrade_sent', required: false)]
    public private(set) ?GiftInfo $giftUpgradeSent = null;

    /**
     * Optional. The domain name of the website on which the user has logged in. More about Telegram Login: https://core.telegram.org/widgets/login
     */
    #[Field('connected_website', required: false)]
    public private(set) ?string $connectedWebsite = null;

    /**
     * Optional. Service message: the user allowed the bot to write messages after adding it to the attachment or side menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method requestWriteAccess
     */
    #[Field('write_access_allowed', required: false)]
    public private(set) ?WriteAccessAllowed $writeAccessAllowed = null;

    /**
     * Optional. Telegram Passport data
     */
    #[Field('passport_data', required: false)]
    public private(set) ?PassportData $passportData = null;

    /**
     * Optional. Service message: a user in the chat triggered another user's proximity alert while sharing Live Location
     */
    #[Field('proximity_alert_triggered', required: false)]
    public private(set) ?ProximityAlertTriggered $proximityAlertTriggered = null;

    /**
     * Optional. Service message: user boosted the chat
     */
    #[Field('boost_added', required: false)]
    public private(set) ?ChatBoostAdded $boostAdded = null;

    /**
     * Optional. Service message: chat background set
     */
    #[Field('chat_background_set', required: false)]
    public private(set) ?ChatBackground $chatBackgroundSet = null;

    /**
     * Optional. Service message: some tasks in a checklist were marked as done or not done
     */
    #[Field('checklist_tasks_done', required: false)]
    public private(set) ?ChecklistTasksDone $checklistTasksDone = null;

    /**
     * Optional. Service message: tasks were added to a checklist
     */
    #[Field('checklist_tasks_added', required: false)]
    public private(set) ?ChecklistTasksAdded $checklistTasksAdded = null;

    /**
     * Optional. Service message: chat or bot added to a Community
     */
    #[Field('community_chat_added', required: false)]
    public private(set) ?CommunityChatAdded $communityChatAdded = null;

    /**
     * Optional. Service message: chat was joined by a user from a Community
     */
    #[Field('community_chat_joined', required: false)]
    public private(set) ?CommunityChatJoined $communityChatJoined = null;

    /**
     * Optional. Service message: chat or bot removed from a Community
     */
    #[Field('community_chat_removed', required: false)]
    public private(set) ?CommunityChatRemoved $communityChatRemoved = null;

    /**
     * Optional. Service message: the price for paid messages in the corresponding direct messages chat of a channel has changed
     */
    #[Field('direct_message_price_changed', required: false)]
    public private(set) ?DirectMessagePriceChanged $directMessagePriceChanged = null;

    /**
     * Optional. Service message: forum topic created
     */
    #[Field('forum_topic_created', required: false)]
    public private(set) ?ForumTopicCreated $forumTopicCreated = null;

    /**
     * Optional. Service message: forum topic edited
     */
    #[Field('forum_topic_edited', required: false)]
    public private(set) ?ForumTopicEdited $forumTopicEdited = null;

    /**
     * Optional. Service message: forum topic closed
     */
    #[Field('forum_topic_closed', required: false)]
    public private(set) ?ForumTopicClosed $forumTopicClosed = null;

    /**
     * Optional. Service message: forum topic reopened
     */
    #[Field('forum_topic_reopened', required: false)]
    public private(set) ?ForumTopicReopened $forumTopicReopened = null;

    /**
     * Optional. Service message: the 'General' forum topic hidden
     */
    #[Field('general_forum_topic_hidden', required: false)]
    public private(set) ?GeneralForumTopicHidden $generalForumTopicHidden = null;

    /**
     * Optional. Service message: the 'General' forum topic unhidden
     */
    #[Field('general_forum_topic_unhidden', required: false)]
    public private(set) ?GeneralForumTopicUnhidden $generalForumTopicUnhidden = null;

    /**
     * Optional. Service message: a scheduled giveaway was created
     */
    #[Field('giveaway_created', required: false)]
    public private(set) ?GiveawayCreated $giveawayCreated = null;

    /**
     * Optional. The message is a scheduled giveaway message
     */
    #[Field('giveaway', required: false)]
    public private(set) ?Giveaway $giveaway = null;

    /**
     * Optional. A giveaway with public winners was completed
     */
    #[Field('giveaway_winners', required: false)]
    public private(set) ?GiveawayWinners $giveawayWinners = null;

    /**
     * Optional. Service message: a giveaway without public winners was completed
     */
    #[Field('giveaway_completed', required: false)]
    public private(set) ?GiveawayCompleted $giveawayCompleted = null;

    /**
     * Optional. Service message: user created a bot that will be managed by the current bot
     */
    #[Field('managed_bot_created', required: false)]
    public private(set) ?ManagedBotCreated $managedBotCreated = null;

    /**
     * Optional. Service message: the price for paid messages has changed in the chat
     */
    #[Field('paid_message_price_changed', required: false)]
    public private(set) ?PaidMessagePriceChanged $paidMessagePriceChanged = null;

    /**
     * Optional. Service message: answer option was added to a poll
     */
    #[Field('poll_option_added', required: false)]
    public private(set) ?PollOptionAdded $pollOptionAdded = null;

    /**
     * Optional. Service message: answer option was deleted from a poll
     */
    #[Field('poll_option_deleted', required: false)]
    public private(set) ?PollOptionDeleted $pollOptionDeleted = null;

    /**
     * Optional. Service message: a suggested post was approved
     */
    #[Field('suggested_post_approved', required: false)]
    public private(set) ?SuggestedPostApproved $suggestedPostApproved = null;

    /**
     * Optional. Service message: approval of a suggested post has failed
     */
    #[Field('suggested_post_approval_failed', required: false)]
    public private(set) ?SuggestedPostApprovalFailed $suggestedPostApprovalFailed = null;

    /**
     * Optional. Service message: a suggested post was declined
     */
    #[Field('suggested_post_declined', required: false)]
    public private(set) ?SuggestedPostDeclined $suggestedPostDeclined = null;

    /**
     * Optional. Service message: payment for a suggested post was received
     */
    #[Field('suggested_post_paid', required: false)]
    public private(set) ?SuggestedPostPaid $suggestedPostPaid = null;

    /**
     * Optional. Service message: payment for a suggested post was refunded
     */
    #[Field('suggested_post_refunded', required: false)]
    public private(set) ?SuggestedPostRefunded $suggestedPostRefunded = null;

    /**
     * Optional. Service message: video chat scheduled
     */
    #[Field('video_chat_scheduled', required: false)]
    public private(set) ?VideoChatScheduled $videoChatScheduled = null;

    /**
     * Optional. Service message: video chat started
     */
    #[Field('video_chat_started', required: false)]
    public private(set) ?VideoChatStarted $videoChatStarted = null;

    /**
     * Optional. Service message: video chat ended
     */
    #[Field('video_chat_ended', required: false)]
    public private(set) ?VideoChatEnded $videoChatEnded = null;

    /**
     * Optional. Service message: new participants invited to a video chat
     */
    #[Field('video_chat_participants_invited', required: false)]
    public private(set) ?VideoChatParticipantsInvited $videoChatParticipantsInvited = null;

    /**
     * Optional. Service message: data sent by a Web App
     */
    #[Field('web_app_data', required: false)]
    public private(set) ?WebAppData $webAppData = null;

    /**
     * Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons.
     */
    #[Field('reply_markup', required: false)]
    public private(set) ?InlineKeyboardMarkup $replyMarkup = null;

}
