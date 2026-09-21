<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Concerns\HasChatHelpers;

/**
 * This object contains full information about a chat.
 *
 * @link https://core.telegram.org/bots/api#chatfullinfo
 */
class ChatFullInfo extends Type
{
    use HasChatHelpers;

    /**
     * Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('id', required: true)]
    public private(set) int $id;

    /**
     * Type of the chat, can be either "private", "group", "supergroup" or "channel"
     */
    #[Field('type', required: true)]
    public private(set) string $type;

    /**
     * Optional. Title, for supergroups, channels and group chats
     */
    #[Field('title', required: false)]
    public private(set) ?string $title = null;

    /**
     * Optional. Username, for private chats, supergroups and channels if available
     */
    #[Field('username', required: false)]
    public private(set) ?string $username = null;

    /**
     * Optional. First name of the other party in a private chat
     */
    #[Field('first_name', required: false)]
    public private(set) ?string $firstName = null;

    /**
     * Optional. Last name of the other party in a private chat
     */
    #[Field('last_name', required: false)]
    public private(set) ?string $lastName = null;

    /**
     * Optional. True, if the supergroup chat is a forum (has topics enabled)
     */
    #[Field('is_forum', required: false)]
    public private(set) ?bool $isForum = null;

    /**
     * Optional. True, if the chat is the direct messages chat of a channel
     */
    #[Field('is_direct_messages', required: false)]
    public private(set) ?bool $isDirectMessages = null;

    /**
     * Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header, and link preview. See accent colors for more details.
     */
    #[Field('accent_color_id', required: true)]
    public private(set) int $accentColorId;

    /**
     * The maximum number of reactions that can be set on a message in the chat
     */
    #[Field('max_reaction_count', required: true)]
    public private(set) int $maxReactionCount;

    /**
     * Optional. Chat photo
     */
    #[Field('photo', required: false)]
    public private(set) ?ChatPhoto $photo = null;

    /**
     * Optional. If non-empty, the list of all active chat usernames; for private chats, supergroups and channels
     * @var String[]|null
     */
    #[Field('active_usernames', required: false)]
    public private(set) ?array $activeUsernames = null;

    /**
     * Optional. For private chats, the date of birth of the user
     */
    #[Field('birthdate', required: false)]
    public private(set) ?Birthdate $birthdate = null;

    /**
     * Optional. For private chats with business accounts, the intro of the business
     */
    #[Field('business_intro', required: false)]
    public private(set) ?BusinessIntro $businessIntro = null;

    /**
     * Optional. For private chats with business accounts, the location of the business
     */
    #[Field('business_location', required: false)]
    public private(set) ?BusinessLocation $businessLocation = null;

    /**
     * Optional. For private chats with business accounts, the opening hours of the business
     */
    #[Field('business_opening_hours', required: false)]
    public private(set) ?BusinessOpeningHours $businessOpeningHours = null;

    /**
     * Optional. For private chats, the personal channel of the user
     */
    #[Field('personal_chat', required: false)]
    public private(set) ?Chat $personalChat = null;

    /**
     * Optional. Information about the corresponding channel chat; for direct messages chats only
     */
    #[Field('parent_chat', required: false)]
    public private(set) ?Chat $parentChat = null;

    /**
     * Optional. List of available reactions allowed in the chat. If omitted, then all emoji reactions are allowed.
     * @var ReactionType[]|null
     */
    #[Field('available_reactions', required: false)]
    #[ArrayOf(ReactionType::class)]
    public private(set) ?array $availableReactions = null;

    /**
     * Optional. Custom emoji identifier of the emoji chosen by the chat for the reply header and link preview background
     */
    #[Field('background_custom_emoji_id', required: false)]
    public private(set) ?string $backgroundCustomEmojiId = null;

    /**
     * Optional. Identifier of the accent color for the chat's profile background. See profile accent colors for more details.
     */
    #[Field('profile_accent_color_id', required: false)]
    public private(set) ?int $profileAccentColorId = null;

    /**
     * Optional. Custom emoji identifier of the emoji chosen by the chat for its profile background
     */
    #[Field('profile_background_custom_emoji_id', required: false)]
    public private(set) ?string $profileBackgroundCustomEmojiId = null;

    /**
     * Optional. Custom emoji identifier of the emoji status of the chat or the other party in a private chat
     */
    #[Field('emoji_status_custom_emoji_id', required: false)]
    public private(set) ?string $emojiStatusCustomEmojiId = null;

    /**
     * Optional. Expiration date of the emoji status of the chat or the other party in a private chat, in Unix time, if any
     */
    #[Field('emoji_status_expiration_date', required: false)]
    public private(set) ?int $emojiStatusExpirationDate = null;

    /**
     * Optional. Bio of the other party in a private chat
     */
    #[Field('bio', required: false)]
    public private(set) ?string $bio = null;

    /**
     * Optional. True, if privacy settings of the other party in the private chat allows to use tg://user?id=<user_id> links only in chats with the user
     */
    #[Field('has_private_forwards', required: false)]
    public private(set) ?bool $hasPrivateForwards = null;

    /**
     * Optional. True, if the privacy settings of the other party restrict sending voice and video note messages in the private chat
     */
    #[Field('has_restricted_voice_and_video_messages', required: false)]
    public private(set) ?bool $hasRestrictedVoiceAndVideoMessages = null;

    /**
     * Optional. True, if users need to join the supergroup before they can send messages
     */
    #[Field('join_to_send_messages', required: false)]
    public private(set) ?bool $joinToSendMessages = null;

    /**
     * Optional. True, if all users directly joining the supergroup without using an invite link need to be approved by supergroup administrators
     */
    #[Field('join_by_request', required: false)]
    public private(set) ?bool $joinByRequest = null;

    /**
     * Optional. Description, for groups, supergroups and channel chats
     */
    #[Field('description', required: false)]
    public private(set) ?string $description = null;

    /**
     * Optional. Primary invite link, for groups, supergroups and channel chats
     */
    #[Field('invite_link', required: false)]
    public private(set) ?string $inviteLink = null;

    /**
     * Optional. The most recent pinned message (by sending date)
     */
    #[Field('pinned_message', required: false)]
    public private(set) ?Message $pinnedMessage = null;

    /**
     * Optional. Default chat member permissions, for groups and supergroups
     */
    #[Field('permissions', required: false)]
    public private(set) ?ChatPermissions $permissions = null;

    /**
     * Information about types of gifts that are accepted by the chat or by the corresponding user for private chats
     */
    #[Field('accepted_gift_types', required: true)]
    public private(set) AcceptedGiftTypes $acceptedGiftTypes;

    /**
     * Optional. True, if paid media messages can be sent or forwarded to the channel chat. The field is available only for channel chats.
     */
    #[Field('can_send_paid_media', required: false)]
    public private(set) ?bool $canSendPaidMedia = null;

    /**
     * Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged user; in seconds
     */
    #[Field('slow_mode_delay', required: false)]
    public private(set) ?int $slowModeDelay = null;

    /**
     * Optional. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to ignore slow mode and chat permissions
     */
    #[Field('unrestrict_boost_count', required: false)]
    public private(set) ?int $unrestrictBoostCount = null;

    /**
     * Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds
     */
    #[Field('message_auto_delete_time', required: false)]
    public private(set) ?int $messageAutoDeleteTime = null;

    /**
     * Optional. True, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to chat administrators.
     */
    #[Field('has_aggressive_anti_spam_enabled', required: false)]
    public private(set) ?bool $hasAggressiveAntiSpamEnabled = null;

    /**
     * Optional. True, if non-administrators can only get the list of bots and administrators in the chat
     */
    #[Field('has_hidden_members', required: false)]
    public private(set) ?bool $hasHiddenMembers = null;

    /**
     * Optional. True, if messages from the chat can't be forwarded to other chats
     */
    #[Field('has_protected_content', required: false)]
    public private(set) ?bool $hasProtectedContent = null;

    /**
     * Optional. True, if new chat members will have access to old messages; available only to chat administrators
     */
    #[Field('has_visible_history', required: false)]
    public private(set) ?bool $hasVisibleHistory = null;

    /**
     * Optional. For supergroups, name of the group sticker set
     */
    #[Field('sticker_set_name', required: false)]
    public private(set) ?string $stickerSetName = null;

    /**
     * Optional. True, if the bot can change the group sticker set
     */
    #[Field('can_set_sticker_set', required: false)]
    public private(set) ?bool $canSetStickerSet = null;

    /**
     * Optional. For supergroups, the name of the group's custom emoji sticker set. Custom emoji from this set can be used by all users and bots in the group.
     */
    #[Field('custom_emoji_sticker_set_name', required: false)]
    public private(set) ?string $customEmojiStickerSetName = null;

    /**
     * Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('linked_chat_id', required: false)]
    public private(set) ?int $linkedChatId = null;

    /**
     * Optional. For supergroups, the location to which the supergroup is connected
     */
    #[Field('location', required: false)]
    public private(set) ?ChatLocation $location = null;

    /**
     * Optional. For private chats, the rating of the user if any
     */
    #[Field('rating', required: false)]
    public private(set) ?UserRating $rating = null;

    /**
     * Optional. For private chats, the first audio added to the profile of the user
     */
    #[Field('first_profile_audio', required: false)]
    public private(set) ?Audio $firstProfileAudio = null;

    /**
     * Optional. The color scheme based on a unique gift that must be used for the chat's name, message replies and link previews
     */
    #[Field('unique_gift_colors', required: false)]
    public private(set) ?UniqueGiftColors $uniqueGiftColors = null;

    /**
     * Optional. The number of Telegram Stars a general user has to pay to send a message to the chat
     */
    #[Field('paid_message_star_count', required: false)]
    public private(set) ?int $paidMessageStarCount = null;

    /**
     * Optional. The bot that processes join request queries in the chat. The field is only available to chat administrators.
     */
    #[Field('guard_bot', required: false)]
    public private(set) ?User $guardBot = null;

    /**
     * Optional. The Community to which the chat belongs
     */
    #[Field('community', required: false)]
    public private(set) ?Community $community = null;

}
