<?php

declare(strict_types=1);

namespace Tueen\Telegram\Contracts;

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
 * @method ArrayResult<Update> getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowedUpdates = null)
 * @method BooleanResult setWebhook(string $url, ?InputFile $certificate = null, ?string $ipAddress = null, ?int $maxConnections = null, ?array $allowedUpdates = null, ?bool $dropPendingUpdates = null, ?string $secretToken = null)
 * @method BooleanResult deleteWebhook(?bool $dropPendingUpdates = null)
 * @method WebhookInfo getWebhookInfo()
 * @method User getMe()
 * @method BooleanResult logOut()
 * @method BooleanResult close()
 * @method Message sendMessage(int|string $chatId, string $text, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message forwardMessage(int|string $chatId, int|string $fromChatId, int $messageId, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method ArrayResult<MessageId> forwardMessages(int|string $chatId, int|string $fromChatId, array $messageIds, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null)
 * @method MessageId copyMessage(int|string $chatId, int|string $fromChatId, int $messageId, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method ArrayResult<MessageId> copyMessages(int|string $chatId, int|string $fromChatId, array $messageIds, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $removeCaption = null)
 * @method Message sendPhoto(int|string $chatId, InputFile|string $photo, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendLivePhoto(int|string $chatId, InputFile|string $livePhoto, InputFile|string $photo, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendAudio(int|string $chatId, InputFile|string $audio, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?string $performer = null, ?string $title = null, InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendDocument(int|string $chatId, InputFile|string $document, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $disableContentTypeDetection = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendVideo(int|string $chatId, InputFile|string $video, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, InputFile|string|null $cover = null, ?int $startTimestamp = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $supportsStreaming = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendAnimation(int|string $chatId, InputFile|string $animation, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendVoice(int|string $chatId, InputFile|string $voice, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendVideoNote(int|string $chatId, InputFile|string $videoNote, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $length = null, InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendPaidMedia(int|string $chatId, int $starCount, array $media, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $payload = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method ArrayResult<Message> sendMediaGroup(int|string $chatId, array $media, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null)
 * @method Message sendLocation(int|string $chatId, float $latitude, float $longitude, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?float $horizontalAccuracy = null, ?int $livePeriod = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendVenue(int|string $chatId, float $latitude, float $longitude, string $title, string $address, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $foursquareId = null, ?string $foursquareType = null, ?string $googlePlaceId = null, ?string $googlePlaceType = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendContact(int|string $chatId, string $phoneNumber, string $firstName, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $lastName = null, ?string $vcard = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendPoll(int|string $chatId, string $question, array $options, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $questionParseMode = null, ?array $questionEntities = null, ?bool $isAnonymous = null, ?string $type = null, ?bool $allowsMultipleAnswers = null, ?bool $allowsRevoting = null, ?bool $shuffleOptions = null, ?bool $allowAddingOptions = null, ?bool $hideResultsUntilCloses = null, ?bool $membersOnly = null, ?array $countryCodes = null, ?array $correctOptionIds = null, ?string $explanation = null, ?string $explanationParseMode = null, ?array $explanationEntities = null, ?InputPollMedia $explanationMedia = null, ?int $openPeriod = null, ?int $closeDate = null, ?bool $isClosed = null, ?string $description = null, ?string $descriptionParseMode = null, ?array $descriptionEntities = null, ?InputPollMedia $media = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Message sendChecklist(string $businessConnectionId, int|string $chatId, InputChecklist $checklist, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message sendDice(int|string $chatId, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method BooleanResult sendMessageDraft(int $chatId, int $draftId, ?int $messageThreadId = null, ?string $text = null, ?string $parseMode = null, ?array $entities = null, ?bool $canStop = null, ?bool $keepOnStop = null)
 * @method BooleanResult sendChatAction(int|string $chatId, string $action, ?string $businessConnectionId = null, ?int $messageThreadId = null)
 * @method BooleanResult setMessageReaction(int|string $chatId, int $messageId, ?array $reaction = null, ?bool $isBig = null)
 * @method UserProfilePhotos getUserProfilePhotos(int $userId, ?int $offset = null, ?int $limit = null)
 * @method UserProfileAudios getUserProfileAudios(int $userId, ?int $offset = null, ?int $limit = null)
 * @method BooleanResult setUserEmojiStatus(int $userId, ?string $emojiStatusCustomEmojiId = null, ?int $emojiStatusExpirationDate = null)
 * @method File getFile(string $fileId)
 * @method BooleanResult banChatMember(int|string $chatId, int $userId, ?int $untilDate = null, ?bool $revokeMessages = null)
 * @method BooleanResult unbanChatMember(int|string $chatId, int $userId, ?bool $onlyIfBanned = null)
 * @method BooleanResult restrictChatMember(int|string $chatId, int $userId, ChatPermissions $permissions, ?bool $useIndependentChatPermissions = null, ?int $untilDate = null)
 * @method BooleanResult promoteChatMember(int|string $chatId, int $userId, ?bool $isAnonymous = null, ?bool $canManageChat = null, ?bool $canDeleteMessages = null, ?bool $canManageVideoChats = null, ?bool $canRestrictMembers = null, ?bool $canPromoteMembers = null, ?bool $canChangeInfo = null, ?bool $canInviteUsers = null, ?bool $canPostStories = null, ?bool $canEditStories = null, ?bool $canDeleteStories = null, ?bool $canPostMessages = null, ?bool $canEditMessages = null, ?bool $canPinMessages = null, ?bool $canManageTopics = null, ?bool $canManageDirectMessages = null, ?bool $canManageTags = null, ?bool $canSendWelcomeMessages = null)
 * @method BooleanResult setChatAdministratorCustomTitle(int|string $chatId, int $userId, string $customTitle)
 * @method BooleanResult setChatMemberTag(int|string $chatId, int $userId, ?string $tag = null)
 * @method BooleanResult banChatSenderChat(int|string $chatId, int $senderChatId)
 * @method BooleanResult unbanChatSenderChat(int|string $chatId, int $senderChatId)
 * @method BooleanResult setChatPermissions(int|string $chatId, ChatPermissions $permissions, ?bool $useIndependentChatPermissions = null)
 * @method StringResult exportChatInviteLink(int|string $chatId)
 * @method ChatInviteLink createChatInviteLink(int|string $chatId, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null)
 * @method ChatInviteLink editChatInviteLink(int|string $chatId, string $inviteLink, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null)
 * @method ChatInviteLink createChatSubscriptionInviteLink(int|string $chatId, int $subscriptionPeriod, int $subscriptionPrice, ?string $name = null)
 * @method ChatInviteLink editChatSubscriptionInviteLink(int|string $chatId, string $inviteLink, ?string $name = null)
 * @method ChatInviteLink revokeChatInviteLink(int|string $chatId, string $inviteLink)
 * @method BooleanResult approveChatJoinRequest(int|string $chatId, int $userId)
 * @method BooleanResult declineChatJoinRequest(int|string $chatId, int $userId)
 * @method BooleanResult answerChatJoinRequestQuery(string $chatJoinRequestQueryId, string $result)
 * @method BooleanResult sendChatJoinRequestWebApp(string $chatJoinRequestQueryId, string $webAppUrl)
 * @method BooleanResult setChatPhoto(int|string $chatId, InputFile $photo)
 * @method BooleanResult deleteChatPhoto(int|string $chatId)
 * @method BooleanResult setChatTitle(int|string $chatId, string $title)
 * @method BooleanResult setChatDescription(int|string $chatId, ?string $description = null)
 * @method BooleanResult pinChatMessage(int|string $chatId, int $messageId, ?string $businessConnectionId = null, ?bool $disableNotification = null)
 * @method BooleanResult unpinChatMessage(int|string $chatId, ?string $businessConnectionId = null, ?int $messageId = null)
 * @method BooleanResult unpinAllChatMessages(int|string $chatId)
 * @method BooleanResult leaveChat(int|string $chatId)
 * @method ChatFullInfo getChat(int|string $chatId)
 * @method ArrayResult<ChatMember> getChatAdministrators(int|string $chatId, ?bool $returnBots = null)
 * @method IntegerResult getChatMemberCount(int|string $chatId)
 * @method ChatMember getChatMember(int|string $chatId, int $userId)
 * @method ArrayResult<Message> getUserPersonalChatMessages(int $userId, int $limit)
 * @method BooleanResult setChatStickerSet(int|string $chatId, string $stickerSetName)
 * @method BooleanResult deleteChatStickerSet(int|string $chatId)
 * @method ArrayResult<Sticker> getForumTopicIconStickers()
 * @method ForumTopic createForumTopic(int|string $chatId, string $name, ?int $iconColor = null, ?string $iconCustomEmojiId = null)
 * @method BooleanResult editForumTopic(int|string $chatId, int $messageThreadId, ?string $name = null, ?string $iconCustomEmojiId = null)
 * @method BooleanResult closeForumTopic(int|string $chatId, int $messageThreadId)
 * @method BooleanResult reopenForumTopic(int|string $chatId, int $messageThreadId)
 * @method BooleanResult deleteForumTopic(int|string $chatId, int $messageThreadId)
 * @method BooleanResult unpinAllForumTopicMessages(int|string $chatId, int $messageThreadId)
 * @method BooleanResult editGeneralForumTopic(int|string $chatId, string $name)
 * @method BooleanResult closeGeneralForumTopic(int|string $chatId)
 * @method BooleanResult reopenGeneralForumTopic(int|string $chatId)
 * @method BooleanResult hideGeneralForumTopic(int|string $chatId)
 * @method BooleanResult unhideGeneralForumTopic(int|string $chatId)
 * @method BooleanResult unpinAllGeneralForumTopicMessages(int|string $chatId)
 * @method BooleanResult answerCallbackQuery(string $callbackQueryId, ?string $text = null, ?bool $showAlert = null, ?string $url = null, ?int $cacheTime = null)
 * @method SentGuestMessage answerGuestQuery(string $guestQueryId, InlineQueryResult $result)
 * @method UserChatBoosts getUserChatBoosts(int|string $chatId, int $userId)
 * @method BusinessConnection getBusinessConnection(string $businessConnectionId)
 * @method StringResult getManagedBotToken(int $userId)
 * @method StringResult replaceManagedBotToken(int $userId)
 * @method BotAccessSettings getManagedBotAccessSettings(int $userId)
 * @method BooleanResult setManagedBotAccessSettings(int $userId, bool $isAccessRestricted, ?array $addedUserIds = null)
 * @method BooleanResult setMyCommands(array $commands, ?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method BooleanResult deleteMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method ArrayResult<BotCommand> getMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method BooleanResult setMyName(?string $name = null, ?string $languageCode = null)
 * @method BotName getMyName(?string $languageCode = null)
 * @method BooleanResult setMyDescription(?string $description = null, ?string $languageCode = null)
 * @method BotDescription getMyDescription(?string $languageCode = null)
 * @method BooleanResult setMyShortDescription(?string $shortDescription = null, ?string $languageCode = null)
 * @method BotShortDescription getMyShortDescription(?string $languageCode = null)
 * @method BooleanResult setMyProfilePhoto(InputProfilePhoto $photo)
 * @method BooleanResult removeMyProfilePhoto()
 * @method BooleanResult setChatMenuButton(?int $chatId = null, ?MenuButton $menuButton = null)
 * @method MenuButton getChatMenuButton(?int $chatId = null)
 * @method BooleanResult setMyDefaultAdministratorRights(?ChatAdministratorRights $rights = null, ?bool $forChannels = null)
 * @method ChatAdministratorRights getMyDefaultAdministratorRights(?bool $forChannels = null)
 * @method Gifts getAvailableGifts()
 * @method BooleanResult sendGift(string $giftId, ?int $userId = null, int|string|null $chatId = null, ?bool $payForUpgrade = null, ?string $text = null, ?string $textParseMode = null, ?array $textEntities = null)
 * @method BooleanResult giftPremiumSubscription(int $userId, int $monthCount, int $starCount, ?string $text = null, ?string $textParseMode = null, ?array $textEntities = null)
 * @method BooleanResult verifyUser(int $userId, ?string $customDescription = null)
 * @method BooleanResult verifyChat(int|string $chatId, ?string $customDescription = null)
 * @method BooleanResult removeUserVerification(int $userId)
 * @method BooleanResult removeChatVerification(int|string $chatId)
 * @method BooleanResult readBusinessMessage(string $businessConnectionId, int $chatId, int $messageId)
 * @method BooleanResult deleteBusinessMessages(string $businessConnectionId, array $messageIds)
 * @method BooleanResult setBusinessAccountName(string $businessConnectionId, string $firstName, ?string $lastName = null)
 * @method BooleanResult setBusinessAccountUsername(string $businessConnectionId, ?string $username = null)
 * @method BooleanResult setBusinessAccountBio(string $businessConnectionId, ?string $bio = null)
 * @method BooleanResult setBusinessAccountProfilePhoto(string $businessConnectionId, InputProfilePhoto $photo, ?bool $isPublic = null)
 * @method BooleanResult removeBusinessAccountProfilePhoto(string $businessConnectionId, ?bool $isPublic = null)
 * @method BooleanResult setBusinessAccountGiftSettings(string $businessConnectionId, bool $showGiftButton, AcceptedGiftTypes $acceptedGiftTypes)
 * @method StarAmount getBusinessAccountStarBalance(string $businessConnectionId)
 * @method BooleanResult transferBusinessAccountStars(string $businessConnectionId, int $starCount)
 * @method OwnedGifts getBusinessAccountGifts(string $businessConnectionId, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeUnique = null, ?bool $excludeFromBlockchain = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null)
 * @method OwnedGifts getUserGifts(int $userId, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null)
 * @method OwnedGifts getChatGifts(int|string $chatId, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null)
 * @method BooleanResult convertGiftToStars(string $businessConnectionId, string $ownedGiftId)
 * @method BooleanResult upgradeGift(string $businessConnectionId, string $ownedGiftId, ?bool $keepOriginalDetails = null, ?int $starCount = null)
 * @method BooleanResult transferGift(string $businessConnectionId, string $ownedGiftId, int $newOwnerChatId, ?int $starCount = null)
 * @method Story postStory(string $businessConnectionId, InputStoryContent $content, int $activePeriod, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?array $areas = null, ?bool $postToChatPage = null, ?bool $protectContent = null)
 * @method Story repostStory(string $businessConnectionId, int $fromChatId, int $fromStoryId, int $activePeriod, ?bool $postToChatPage = null, ?bool $protectContent = null)
 * @method Story editStory(string $businessConnectionId, int $storyId, InputStoryContent $content, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?array $areas = null)
 * @method BooleanResult deleteStory(string $businessConnectionId, int $storyId)
 * @method SentWebAppMessage answerWebAppQuery(string $webAppQueryId, InlineQueryResult $result)
 * @method PreparedInlineMessage savePreparedInlineMessage(int $userId, InlineQueryResult $result, ?bool $allowUserChats = null, ?bool $allowBotChats = null, ?bool $allowGroupChats = null, ?bool $allowChannelChats = null)
 * @method PreparedKeyboardButton savePreparedKeyboardButton(int $userId, KeyboardButton $button)
 * @method Message|BooleanResult editMessageText(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $text = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InputRichMessage $richMessage = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message|BooleanResult editMessageCaption(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message|BooleanResult editMessageMedia(InputMedia $media, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message|BooleanResult editMessageLiveLocation(float $latitude, float $longitude, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?int $livePeriod = null, ?float $horizontalAccuracy = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message|BooleanResult stopMessageLiveLocation(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message editMessageChecklist(string $businessConnectionId, int|string $chatId, int $messageId, InputChecklist $checklist, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message|BooleanResult editMessageReplyMarkup(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Poll stopPoll(int|string $chatId, int $messageId, ?string $businessConnectionId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method BooleanResult editEphemeralMessageText(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?string $text = null, ?string $parseMode = null, ?array $entities = null, ?InputRichMessage $richMessage = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method BooleanResult editEphemeralMessageMedia(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, InputMedia $media, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method BooleanResult editEphemeralMessageCaption(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method BooleanResult editEphemeralMessageReplyMarkup(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method BooleanResult approveSuggestedPost(int $chatId, int $messageId, ?int $sendDate = null)
 * @method BooleanResult declineSuggestedPost(int $chatId, int $messageId, ?string $comment = null)
 * @method BooleanResult deleteMessage(int|string $chatId, int $messageId)
 * @method BooleanResult deleteMessages(int|string $chatId, array $messageIds)
 * @method BooleanResult deleteEphemeralMessage(int|string $chatId, int $receiverUserId, int $ephemeralMessageId)
 * @method BooleanResult deleteMessageReaction(int|string $chatId, int $messageId, ?int $userId = null, ?int $actorChatId = null)
 * @method BooleanResult deleteAllMessageReactions(int|string $chatId, ?int $userId = null, ?int $actorChatId = null)
 * @method Message sendSticker(int|string $chatId, InputFile|string $sticker, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method StickerSet getStickerSet(string $name)
 * @method ArrayResult<Sticker> getCustomEmojiStickers(array $customEmojiIds)
 * @method File uploadStickerFile(int $userId, InputFile $sticker, string $stickerFormat)
 * @method BooleanResult createNewStickerSet(int $userId, string $name, string $title, array $stickers, ?string $stickerType = null, ?bool $needsRepainting = null)
 * @method BooleanResult addStickerToSet(int $userId, string $name, InputSticker $sticker)
 * @method BooleanResult setStickerPositionInSet(string $sticker, int $position)
 * @method BooleanResult deleteStickerFromSet(string $sticker)
 * @method BooleanResult replaceStickerInSet(int $userId, string $name, string $oldSticker, InputSticker $sticker)
 * @method BooleanResult setStickerEmojiList(string $sticker, array $emojiList)
 * @method BooleanResult setStickerKeywords(string $sticker, ?array $keywords = null)
 * @method BooleanResult setStickerMaskPosition(string $sticker, ?MaskPosition $maskPosition = null)
 * @method BooleanResult setStickerSetTitle(string $name, string $title)
 * @method BooleanResult setStickerSetThumbnail(string $name, int $userId, string $format, InputFile|string|null $thumbnail = null)
 * @method BooleanResult setCustomEmojiStickerSetThumbnail(string $name, ?string $customEmojiId = null)
 * @method BooleanResult deleteStickerSet(string $name)
 * @method Message sendRichMessage(int|string $chatId, InputRichMessage $richMessage, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method BooleanResult sendRichMessageDraft(int $chatId, int $draftId, InputRichMessage $richMessage, ?int $messageThreadId = null, ?bool $canStop = null, ?bool $keepOnStop = null)
 * @method BooleanResult answerInlineQuery(string $inlineQueryId, array $results, ?int $cacheTime = null, ?bool $isPersonal = null, ?string $nextOffset = null, ?InlineQueryResultsButton $button = null)
 * @method Message sendInvoice(int|string $chatId, string $title, string $description, string $payload, string $currency, array $prices, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $providerToken = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $startParameter = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method StringResult createInvoiceLink(string $title, string $description, string $payload, string $currency, array $prices, ?string $businessConnectionId = null, ?string $providerToken = null, ?int $subscriptionPeriod = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null)
 * @method BooleanResult answerShippingQuery(string $shippingQueryId, bool $ok, ?array $shippingOptions = null, ?string $errorMessage = null)
 * @method BooleanResult answerPreCheckoutQuery(string $preCheckoutQueryId, bool $ok, ?string $errorMessage = null)
 * @method StarAmount getMyStarBalance()
 * @method StarTransactions getStarTransactions(?int $offset = null, ?int $limit = null)
 * @method BooleanResult refundStarPayment(int $userId, string $telegramPaymentChargeId)
 * @method BooleanResult editUserStarSubscription(int $userId, string $telegramPaymentChargeId, bool $isCanceled)
 * @method BooleanResult setPassportDataErrors(int $userId, array $errors)
 * @method Message sendGame(int|string $chatId, string $gameShortName, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Message|BooleanResult setGameScore(int $userId, int $score, ?bool $force = null, ?bool $disableEditMessage = null, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null)
 * @method ArrayResult<GameHighScore> getGameHighScores(int $userId, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null)
 */
abstract class TelegramMethods
{
}
