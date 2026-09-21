<?php

declare(strict_types=1);

namespace Tueen\Telegram\Contracts;

use Tueen\Telegram\Enums\ChatAction;
use Tueen\Telegram\Enums\ChatJoinRequestResult;
use Tueen\Telegram\Enums\Currency;
use Tueen\Telegram\Enums\DiceEmoji;
use Tueen\Telegram\Enums\ForumIconColor;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Enums\PollType;
use Tueen\Telegram\Enums\StickerFormat;
use Tueen\Telegram\Enums\StickerType;
use Tueen\Telegram\Enums\StoryActivePeriod;
use Tueen\Telegram\Types\AcceptedGiftTypes;
use Tueen\Telegram\Types\BotAccessSettings;
use Tueen\Telegram\Types\BotCommand;
use Tueen\Telegram\Types\BotCommandScope;
use Tueen\Telegram\Types\BotDescription;
use Tueen\Telegram\Types\BotName;
use Tueen\Telegram\Types\BotShortDescription;
use Tueen\Telegram\Types\BusinessConnection;
use Tueen\Telegram\Types\ChatAdministratorRights;
use Tueen\Telegram\Types\ChatFullInfo;
use Tueen\Telegram\Types\ChatInviteLink;
use Tueen\Telegram\Types\ChatMember;
use Tueen\Telegram\Types\ChatPermissions;
use Tueen\Telegram\Types\Custom\ArrayResult;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\Custom\IntegerResult;
use Tueen\Telegram\Types\Custom\StringResult;
use Tueen\Telegram\Types\EphemeralMessageParameters;
use Tueen\Telegram\Types\Error;
use Tueen\Telegram\Types\File;
use Tueen\Telegram\Types\ForceReply;
use Tueen\Telegram\Types\ForumTopic;
use Tueen\Telegram\Types\GameHighScore;
use Tueen\Telegram\Types\Gifts;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InlineQueryResult;
use Tueen\Telegram\Types\InlineQueryResultsButton;
use Tueen\Telegram\Types\InputChecklist;
use Tueen\Telegram\Types\InputMedia;
use Tueen\Telegram\Types\InputPollMedia;
use Tueen\Telegram\Types\InputProfilePhoto;
use Tueen\Telegram\Types\InputRichMessage;
use Tueen\Telegram\Types\InputSticker;
use Tueen\Telegram\Types\InputStoryContent;
use Tueen\Telegram\Types\KeyboardButton;
use Tueen\Telegram\Types\LinkPreviewOptions;
use Tueen\Telegram\Types\MaskPosition;
use Tueen\Telegram\Types\MenuButton;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\MessageId;
use Tueen\Telegram\Types\OwnedGifts;
use Tueen\Telegram\Types\Poll;
use Tueen\Telegram\Types\PreparedInlineMessage;
use Tueen\Telegram\Types\PreparedKeyboardButton;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\SentGuestMessage;
use Tueen\Telegram\Types\SentWebAppMessage;
use Tueen\Telegram\Types\StarAmount;
use Tueen\Telegram\Types\StarTransactions;
use Tueen\Telegram\Types\Sticker;
use Tueen\Telegram\Types\StickerSet;
use Tueen\Telegram\Types\Story;
use Tueen\Telegram\Types\SuggestedPostParameters;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\UserChatBoosts;
use Tueen\Telegram\Types\UserProfileAudios;
use Tueen\Telegram\Types\UserProfilePhotos;
use Tueen\Telegram\Types\WebhookInfo;

/**
 * Dynamic Telegram Bot API 10.3 Methods Mixin.
 *
 * This contract defines all 185 Telegram Bot API method signatures for IDE autocompletion,
 * parameter hints, and type safety, keeping the core Telegram client facade lightweight.
 *
 * @method ArrayResult<Update>|Error getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowedUpdates = null, mixed ...$extra)
 * @method BooleanResult|Error setWebhook(string $url, ?InputFile $certificate = null, ?string $ipAddress = null, ?int $maxConnections = null, ?array $allowedUpdates = null, ?bool $dropPendingUpdates = null, ?string $secretToken = null, mixed ...$extra)
 * @method BooleanResult|Error deleteWebhook(?bool $dropPendingUpdates = null, mixed ...$extra)
 * @method WebhookInfo|Error getWebhookInfo(mixed ...$extra)
 * @method User|Error getMe(mixed ...$extra)
 * @method BooleanResult|Error logOut(mixed ...$extra)
 * @method BooleanResult|Error close(mixed ...$extra)
 * @method Message|Error sendMessage(int|string $chatId, string $text, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error forwardMessage(int|string $chatId, int|string $fromChatId, int $messageId, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, mixed ...$extra)
 * @method ArrayResult<MessageId>|Error forwardMessages(int|string $chatId, int|string $fromChatId, array $messageIds, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, mixed ...$extra)
 * @method MessageId|Error copyMessage(int|string $chatId, int|string $fromChatId, int $messageId, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method ArrayResult<MessageId>|Error copyMessages(int|string $chatId, int|string $fromChatId, array $messageIds, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $removeCaption = null, mixed ...$extra)
 * @method Message|Error sendPhoto(int|string $chatId, InputFile|string $photo, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendLivePhoto(int|string $chatId, InputFile|string $livePhoto, InputFile|string $photo, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendAudio(int|string $chatId, InputFile|string $audio, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?string $performer = null, ?string $title = null, InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendDocument(int|string $chatId, InputFile|string $document, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $disableContentTypeDetection = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendVideo(int|string $chatId, InputFile|string $video, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, InputFile|string|null $cover = null, ?int $startTimestamp = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $supportsStreaming = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendAnimation(int|string $chatId, InputFile|string $animation, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendVoice(int|string $chatId, InputFile|string $voice, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendVideoNote(int|string $chatId, InputFile|string $videoNote, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $length = null, InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendPaidMedia(int|string $chatId, int $starCount, array $media, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $payload = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method ArrayResult<Message>|Error sendMediaGroup(int|string $chatId, array $media, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, mixed ...$extra)
 * @method Message|Error sendLocation(int|string $chatId, float $latitude, float $longitude, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?float $horizontalAccuracy = null, ?int $livePeriod = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendVenue(int|string $chatId, float $latitude, float $longitude, string $title, string $address, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $foursquareId = null, ?string $foursquareType = null, ?string $googlePlaceId = null, ?string $googlePlaceType = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendContact(int|string $chatId, string $phoneNumber, string $firstName, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $lastName = null, ?string $vcard = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendPoll(int|string $chatId, string $question, array $options, ?string $businessConnectionId = null, ?int $messageThreadId = null, ParseMode|string|null $questionParseMode = null, ?array $questionEntities = null, ?bool $isAnonymous = null, PollType|string|null $type = null, ?bool $allowsMultipleAnswers = null, ?bool $allowsRevoting = null, ?bool $shuffleOptions = null, ?bool $allowAddingOptions = null, ?bool $hideResultsUntilCloses = null, ?bool $membersOnly = null, ?array $countryCodes = null, ?array $correctOptionIds = null, ?string $explanation = null, ParseMode|string|null $explanationParseMode = null, ?array $explanationEntities = null, ?InputPollMedia $explanationMedia = null, ?int $openPeriod = null, ?int $closeDate = null, ?bool $isClosed = null, ?string $description = null, ParseMode|string|null $descriptionParseMode = null, ?array $descriptionEntities = null, ?InputPollMedia $media = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendChecklist(string $businessConnectionId, int|string $chatId, InputChecklist $checklist, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|Error sendDice(int|string $chatId, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, DiceEmoji|string|null $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error sendMessageDraft(int $chatId, int $draftId, ?int $messageThreadId = null, ?string $text = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?bool $canStop = null, ?bool $keepOnStop = null, mixed ...$extra)
 * @method BooleanResult|Error sendChatAction(int|string $chatId, ChatAction|string $action, ?string $businessConnectionId = null, ?int $messageThreadId = null, mixed ...$extra)
 * @method BooleanResult|Error setMessageReaction(int|string $chatId, int $messageId, ?array $reaction = null, ?bool $isBig = null, mixed ...$extra)
 * @method UserProfilePhotos|Error getUserProfilePhotos(int $userId, ?int $offset = null, ?int $limit = null, mixed ...$extra)
 * @method UserProfileAudios|Error getUserProfileAudios(int $userId, ?int $offset = null, ?int $limit = null, mixed ...$extra)
 * @method BooleanResult|Error setUserEmojiStatus(int $userId, ?string $emojiStatusCustomEmojiId = null, ?int $emojiStatusExpirationDate = null, mixed ...$extra)
 * @method File|Error getFile(string $fileId, mixed ...$extra)
 * @method BooleanResult|Error banChatMember(int|string $chatId, int $userId, ?int $untilDate = null, ?bool $revokeMessages = null, mixed ...$extra)
 * @method BooleanResult|Error unbanChatMember(int|string $chatId, int $userId, ?bool $onlyIfBanned = null, mixed ...$extra)
 * @method BooleanResult|Error restrictChatMember(int|string $chatId, int $userId, ChatPermissions $permissions, ?bool $useIndependentChatPermissions = null, ?int $untilDate = null, mixed ...$extra)
 * @method BooleanResult|Error promoteChatMember(int|string $chatId, int $userId, ?bool $isAnonymous = null, ?bool $canManageChat = null, ?bool $canDeleteMessages = null, ?bool $canManageVideoChats = null, ?bool $canRestrictMembers = null, ?bool $canPromoteMembers = null, ?bool $canChangeInfo = null, ?bool $canInviteUsers = null, ?bool $canPostStories = null, ?bool $canEditStories = null, ?bool $canDeleteStories = null, ?bool $canPostMessages = null, ?bool $canEditMessages = null, ?bool $canPinMessages = null, ?bool $canManageTopics = null, ?bool $canManageDirectMessages = null, ?bool $canManageTags = null, ?bool $canSendWelcomeMessages = null, mixed ...$extra)
 * @method BooleanResult|Error setChatAdministratorCustomTitle(int|string $chatId, int $userId, string $customTitle, mixed ...$extra)
 * @method BooleanResult|Error setChatMemberTag(int|string $chatId, int $userId, ?string $tag = null, mixed ...$extra)
 * @method BooleanResult|Error banChatSenderChat(int|string $chatId, int $senderChatId, mixed ...$extra)
 * @method BooleanResult|Error unbanChatSenderChat(int|string $chatId, int $senderChatId, mixed ...$extra)
 * @method BooleanResult|Error setChatPermissions(int|string $chatId, ChatPermissions $permissions, ?bool $useIndependentChatPermissions = null, mixed ...$extra)
 * @method StringResult|Error exportChatInviteLink(int|string $chatId, mixed ...$extra)
 * @method ChatInviteLink|Error createChatInviteLink(int|string $chatId, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null, mixed ...$extra)
 * @method ChatInviteLink|Error editChatInviteLink(int|string $chatId, string $inviteLink, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null, mixed ...$extra)
 * @method ChatInviteLink|Error createChatSubscriptionInviteLink(int|string $chatId, int $subscriptionPeriod, int $subscriptionPrice, ?string $name = null, mixed ...$extra)
 * @method ChatInviteLink|Error editChatSubscriptionInviteLink(int|string $chatId, string $inviteLink, ?string $name = null, mixed ...$extra)
 * @method ChatInviteLink|Error revokeChatInviteLink(int|string $chatId, string $inviteLink, mixed ...$extra)
 * @method BooleanResult|Error approveChatJoinRequest(int|string $chatId, int $userId, mixed ...$extra)
 * @method BooleanResult|Error declineChatJoinRequest(int|string $chatId, int $userId, mixed ...$extra)
 * @method BooleanResult|Error answerChatJoinRequestQuery(string $chatJoinRequestQueryId, ChatJoinRequestResult|string $result, mixed ...$extra)
 * @method BooleanResult|Error sendChatJoinRequestWebApp(string $chatJoinRequestQueryId, string $webAppUrl, mixed ...$extra)
 * @method BooleanResult|Error setChatPhoto(int|string $chatId, InputFile $photo, mixed ...$extra)
 * @method BooleanResult|Error deleteChatPhoto(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error setChatTitle(int|string $chatId, string $title, mixed ...$extra)
 * @method BooleanResult|Error setChatDescription(int|string $chatId, ?string $description = null, mixed ...$extra)
 * @method BooleanResult|Error pinChatMessage(int|string $chatId, int $messageId, ?string $businessConnectionId = null, ?bool $disableNotification = null, mixed ...$extra)
 * @method BooleanResult|Error unpinChatMessage(int|string $chatId, ?string $businessConnectionId = null, ?int $messageId = null, mixed ...$extra)
 * @method BooleanResult|Error unpinAllChatMessages(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error leaveChat(int|string $chatId, mixed ...$extra)
 * @method ChatFullInfo|Error getChat(int|string $chatId, mixed ...$extra)
 * @method ArrayResult<ChatMember>|Error getChatAdministrators(int|string $chatId, ?bool $returnBots = null, mixed ...$extra)
 * @method IntegerResult|Error getChatMemberCount(int|string $chatId, mixed ...$extra)
 * @method ChatMember|Error getChatMember(int|string $chatId, int $userId, mixed ...$extra)
 * @method ArrayResult<Message>|Error getUserPersonalChatMessages(int $userId, int $limit, mixed ...$extra)
 * @method BooleanResult|Error setChatStickerSet(int|string $chatId, string $stickerSetName, mixed ...$extra)
 * @method BooleanResult|Error deleteChatStickerSet(int|string $chatId, mixed ...$extra)
 * @method ArrayResult<Sticker>|Error getForumTopicIconStickers(mixed ...$extra)
 * @method ForumTopic|Error createForumTopic(int|string $chatId, string $name, ForumIconColor|int|null $iconColor = null, ?string $iconCustomEmojiId = null, mixed ...$extra)
 * @method BooleanResult|Error editForumTopic(int|string $chatId, int $messageThreadId, ?string $name = null, ?string $iconCustomEmojiId = null, mixed ...$extra)
 * @method BooleanResult|Error closeForumTopic(int|string $chatId, int $messageThreadId, mixed ...$extra)
 * @method BooleanResult|Error reopenForumTopic(int|string $chatId, int $messageThreadId, mixed ...$extra)
 * @method BooleanResult|Error deleteForumTopic(int|string $chatId, int $messageThreadId, mixed ...$extra)
 * @method BooleanResult|Error unpinAllForumTopicMessages(int|string $chatId, int $messageThreadId, mixed ...$extra)
 * @method BooleanResult|Error editGeneralForumTopic(int|string $chatId, string $name, mixed ...$extra)
 * @method BooleanResult|Error closeGeneralForumTopic(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error reopenGeneralForumTopic(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error hideGeneralForumTopic(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error unhideGeneralForumTopic(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error unpinAllGeneralForumTopicMessages(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error answerCallbackQuery(string $callbackQueryId, ?string $text = null, ?bool $showAlert = null, ?string $url = null, ?int $cacheTime = null, mixed ...$extra)
 * @method SentGuestMessage|Error answerGuestQuery(string $guestQueryId, InlineQueryResult $result, mixed ...$extra)
 * @method UserChatBoosts|Error getUserChatBoosts(int|string $chatId, int $userId, mixed ...$extra)
 * @method BusinessConnection|Error getBusinessConnection(string $businessConnectionId, mixed ...$extra)
 * @method StringResult|Error getManagedBotToken(int $userId, mixed ...$extra)
 * @method StringResult|Error replaceManagedBotToken(int $userId, mixed ...$extra)
 * @method BotAccessSettings|Error getManagedBotAccessSettings(int $userId, mixed ...$extra)
 * @method BooleanResult|Error setManagedBotAccessSettings(int $userId, bool $isAccessRestricted, ?array $addedUserIds = null, mixed ...$extra)
 * @method BooleanResult|Error setMyCommands(array $commands, ?BotCommandScope $scope = null, ?string $languageCode = null, mixed ...$extra)
 * @method BooleanResult|Error deleteMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null, mixed ...$extra)
 * @method ArrayResult<BotCommand>|Error getMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null, mixed ...$extra)
 * @method BooleanResult|Error setMyName(?string $name = null, ?string $languageCode = null, mixed ...$extra)
 * @method BotName|Error getMyName(?string $languageCode = null, mixed ...$extra)
 * @method BooleanResult|Error setMyDescription(?string $description = null, ?string $languageCode = null, mixed ...$extra)
 * @method BotDescription|Error getMyDescription(?string $languageCode = null, mixed ...$extra)
 * @method BooleanResult|Error setMyShortDescription(?string $shortDescription = null, ?string $languageCode = null, mixed ...$extra)
 * @method BotShortDescription|Error getMyShortDescription(?string $languageCode = null, mixed ...$extra)
 * @method BooleanResult|Error setMyProfilePhoto(InputProfilePhoto $photo, mixed ...$extra)
 * @method BooleanResult|Error removeMyProfilePhoto(mixed ...$extra)
 * @method BooleanResult|Error setChatMenuButton(?int $chatId = null, ?MenuButton $menuButton = null, mixed ...$extra)
 * @method MenuButton|Error getChatMenuButton(?int $chatId = null, mixed ...$extra)
 * @method BooleanResult|Error setMyDefaultAdministratorRights(?ChatAdministratorRights $rights = null, ?bool $forChannels = null, mixed ...$extra)
 * @method ChatAdministratorRights|Error getMyDefaultAdministratorRights(?bool $forChannels = null, mixed ...$extra)
 * @method Gifts|Error getAvailableGifts(mixed ...$extra)
 * @method BooleanResult|Error sendGift(string $giftId, ?int $userId = null, int|string|null $chatId = null, ?bool $payForUpgrade = null, ?string $text = null, ParseMode|string|null $textParseMode = null, ?array $textEntities = null, mixed ...$extra)
 * @method BooleanResult|Error giftPremiumSubscription(int $userId, int $monthCount, int $starCount, ?string $text = null, ParseMode|string|null $textParseMode = null, ?array $textEntities = null, mixed ...$extra)
 * @method BooleanResult|Error verifyUser(int $userId, ?string $customDescription = null, mixed ...$extra)
 * @method BooleanResult|Error verifyChat(int|string $chatId, ?string $customDescription = null, mixed ...$extra)
 * @method BooleanResult|Error removeUserVerification(int $userId, mixed ...$extra)
 * @method BooleanResult|Error removeChatVerification(int|string $chatId, mixed ...$extra)
 * @method BooleanResult|Error readBusinessMessage(string $businessConnectionId, int $chatId, int $messageId, mixed ...$extra)
 * @method BooleanResult|Error deleteBusinessMessages(string $businessConnectionId, array $messageIds, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountName(string $businessConnectionId, string $firstName, ?string $lastName = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountUsername(string $businessConnectionId, ?string $username = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountBio(string $businessConnectionId, ?string $bio = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountProfilePhoto(string $businessConnectionId, InputProfilePhoto $photo, ?bool $isPublic = null, mixed ...$extra)
 * @method BooleanResult|Error removeBusinessAccountProfilePhoto(string $businessConnectionId, ?bool $isPublic = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountGiftSettings(string $businessConnectionId, bool $showGiftButton, AcceptedGiftTypes $acceptedGiftTypes, mixed ...$extra)
 * @method StarAmount|Error getBusinessAccountStarBalance(string $businessConnectionId, mixed ...$extra)
 * @method BooleanResult|Error transferBusinessAccountStars(string $businessConnectionId, int $starCount, mixed ...$extra)
 * @method OwnedGifts|Error getBusinessAccountGifts(string $businessConnectionId, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeUnique = null, ?bool $excludeFromBlockchain = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null, mixed ...$extra)
 * @method OwnedGifts|Error getUserGifts(int $userId, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null, mixed ...$extra)
 * @method OwnedGifts|Error getChatGifts(int|string $chatId, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null, mixed ...$extra)
 * @method BooleanResult|Error convertGiftToStars(string $businessConnectionId, string $ownedGiftId, mixed ...$extra)
 * @method BooleanResult|Error upgradeGift(string $businessConnectionId, string $ownedGiftId, ?bool $keepOriginalDetails = null, ?int $starCount = null, mixed ...$extra)
 * @method BooleanResult|Error transferGift(string $businessConnectionId, string $ownedGiftId, int $newOwnerChatId, ?int $starCount = null, mixed ...$extra)
 * @method Story|Error postStory(string $businessConnectionId, InputStoryContent $content, StoryActivePeriod|int $activePeriod, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?array $areas = null, ?bool $postToChatPage = null, ?bool $protectContent = null, mixed ...$extra)
 * @method Story|Error repostStory(string $businessConnectionId, int $fromChatId, int $fromStoryId, StoryActivePeriod|int $activePeriod, ?bool $postToChatPage = null, ?bool $protectContent = null, mixed ...$extra)
 * @method Story|Error editStory(string $businessConnectionId, int $storyId, InputStoryContent $content, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?array $areas = null, mixed ...$extra)
 * @method BooleanResult|Error deleteStory(string $businessConnectionId, int $storyId, mixed ...$extra)
 * @method SentWebAppMessage|Error answerWebAppQuery(string $webAppQueryId, InlineQueryResult $result, mixed ...$extra)
 * @method PreparedInlineMessage|Error savePreparedInlineMessage(int $userId, InlineQueryResult $result, ?bool $allowUserChats = null, ?bool $allowBotChats = null, ?bool $allowGroupChats = null, ?bool $allowChannelChats = null, mixed ...$extra)
 * @method PreparedKeyboardButton|Error savePreparedKeyboardButton(int $userId, KeyboardButton $button, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageText(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $text = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InputRichMessage $richMessage = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageCaption(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageMedia(InputMedia $media, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageLiveLocation(float $latitude, float $longitude, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?int $livePeriod = null, ?float $horizontalAccuracy = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|BooleanResult|Error stopMessageLiveLocation(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|Error editMessageChecklist(string $businessConnectionId, int|string $chatId, int $messageId, InputChecklist $checklist, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageReplyMarkup(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Poll|Error stopPoll(int|string $chatId, int $messageId, ?string $businessConnectionId = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageText(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?string $text = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?InputRichMessage $richMessage = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageMedia(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, InputMedia $media, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageCaption(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageReplyMarkup(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error approveSuggestedPost(int $chatId, int $messageId, ?int $sendDate = null, mixed ...$extra)
 * @method BooleanResult|Error declineSuggestedPost(int $chatId, int $messageId, ?string $comment = null, mixed ...$extra)
 * @method BooleanResult|Error deleteMessage(int|string $chatId, int $messageId, mixed ...$extra)
 * @method BooleanResult|Error deleteMessages(int|string $chatId, array $messageIds, mixed ...$extra)
 * @method BooleanResult|Error deleteEphemeralMessage(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, mixed ...$extra)
 * @method BooleanResult|Error deleteMessageReaction(int|string $chatId, int $messageId, ?int $userId = null, ?int $actorChatId = null, mixed ...$extra)
 * @method BooleanResult|Error deleteAllMessageReactions(int|string $chatId, ?int $userId = null, ?int $actorChatId = null, mixed ...$extra)
 * @method Message|Error sendSticker(int|string $chatId, InputFile|string $sticker, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method StickerSet|Error getStickerSet(string $name, mixed ...$extra)
 * @method ArrayResult<Sticker>|Error getCustomEmojiStickers(array $customEmojiIds, mixed ...$extra)
 * @method File|Error uploadStickerFile(int $userId, InputFile $sticker, StickerFormat|string $stickerFormat, mixed ...$extra)
 * @method BooleanResult|Error createNewStickerSet(int $userId, string $name, string $title, array $stickers, StickerType|string|null $stickerType = null, ?bool $needsRepainting = null, mixed ...$extra)
 * @method BooleanResult|Error addStickerToSet(int $userId, string $name, InputSticker $sticker, mixed ...$extra)
 * @method BooleanResult|Error setStickerPositionInSet(string $sticker, int $position, mixed ...$extra)
 * @method BooleanResult|Error deleteStickerFromSet(string $sticker, mixed ...$extra)
 * @method BooleanResult|Error replaceStickerInSet(int $userId, string $name, string $oldSticker, InputSticker $sticker, mixed ...$extra)
 * @method BooleanResult|Error setStickerEmojiList(string $sticker, array $emojiList, mixed ...$extra)
 * @method BooleanResult|Error setStickerKeywords(string $sticker, ?array $keywords = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerMaskPosition(string $sticker, ?MaskPosition $maskPosition = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerSetTitle(string $name, string $title, mixed ...$extra)
 * @method BooleanResult|Error setStickerSetThumbnail(string $name, int $userId, StickerFormat|string $format, InputFile|string|null $thumbnail = null, mixed ...$extra)
 * @method BooleanResult|Error setCustomEmojiStickerSetThumbnail(string $name, ?string $customEmojiId = null, mixed ...$extra)
 * @method BooleanResult|Error deleteStickerSet(string $name, mixed ...$extra)
 * @method Message|Error sendRichMessage(int|string $chatId, InputRichMessage $richMessage, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, mixed ...$extra)
 * @method BooleanResult|Error sendRichMessageDraft(int $chatId, int $draftId, InputRichMessage $richMessage, ?int $messageThreadId = null, ?bool $canStop = null, ?bool $keepOnStop = null, mixed ...$extra)
 * @method BooleanResult|Error answerInlineQuery(string $inlineQueryId, array $results, ?int $cacheTime = null, ?bool $isPersonal = null, ?string $nextOffset = null, ?InlineQueryResultsButton $button = null, mixed ...$extra)
 * @method Message|Error sendInvoice(int|string $chatId, string $title, string $description, string $payload, Currency|string $currency, array $prices, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $providerToken = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $startParameter = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method StringResult|Error createInvoiceLink(string $title, string $description, string $payload, Currency|string $currency, array $prices, ?string $businessConnectionId = null, ?string $providerToken = null, ?int $subscriptionPeriod = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, mixed ...$extra)
 * @method BooleanResult|Error answerShippingQuery(string $shippingQueryId, bool $ok, ?array $shippingOptions = null, ?string $errorMessage = null, mixed ...$extra)
 * @method BooleanResult|Error answerPreCheckoutQuery(string $preCheckoutQueryId, bool $ok, ?string $errorMessage = null, mixed ...$extra)
 * @method StarAmount|Error getMyStarBalance(mixed ...$extra)
 * @method StarTransactions|Error getStarTransactions(?int $offset = null, ?int $limit = null, mixed ...$extra)
 * @method BooleanResult|Error refundStarPayment(int $userId, string $telegramPaymentChargeId, mixed ...$extra)
 * @method BooleanResult|Error editUserStarSubscription(int $userId, string $telegramPaymentChargeId, bool $isCanceled, mixed ...$extra)
 * @method BooleanResult|Error setPassportDataErrors(int $userId, array $errors, mixed ...$extra)
 * @method Message|Error sendGame(int|string $chatId, string $gameShortName, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null, mixed ...$extra)
 * @method Message|BooleanResult|Error setGameScore(int $userId, int $score, ?bool $force = null, ?bool $disableEditMessage = null, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, mixed ...$extra)
 * @method ArrayResult<GameHighScore>|Error getGameHighScores(int $userId, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, mixed ...$extra)
 */
abstract class TelegramMethods
{
}
