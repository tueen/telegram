<?php

declare(strict_types=1);

namespace Tueen\Telegram;

use BadMethodCallException;
use Closure;
use Generator;
use ReflectionClass;
use Tueen\Telegram\Client\GuzzleHttpClient;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Pipeline\LoggingMiddleware;
use Tueen\Telegram\Pipeline\MiddlewareInterface;
use Tueen\Telegram\Pipeline\Pipeline;
use Tueen\Telegram\Pipeline\RetryMiddleware;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\File;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\Update;

/**
 * Tueen Telegram Client - The Royal Client for Telegram Bot API.
 *
 * @method Types\Update[] getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowedUpdates = null)
 * @method Types\Type setWebhook(string $url, ?Types\Custom\InputFile $certificate = null, ?string $ipAddress = null, ?int $maxConnections = null, ?array $allowedUpdates = null, ?bool $dropPendingUpdates = null, ?string $secretToken = null)
 * @method Types\Type deleteWebhook(?bool $dropPendingUpdates = null)
 * @method Types\WebhookInfo getWebhookInfo()
 * @method Types\User getMe()
 * @method Types\Type logOut()
 * @method Types\Type close()
 * @method Types\Message sendMessage(int|string $chatId, string $text, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message forwardMessage(int|string $chatId, int|string $fromChatId, int $messageId, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method Types\MessageId[] forwardMessages(int|string $chatId, int|string $fromChatId, array $messageIds, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null)
 * @method Types\MessageId copyMessage(int|string $chatId, int|string $fromChatId, int $messageId, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\MessageId[] copyMessages(int|string $chatId, int|string $fromChatId, array $messageIds, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $removeCaption = null)
 * @method Types\Message sendPhoto(int|string $chatId, Types\Custom\InputFile|string $photo, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendLivePhoto(int|string $chatId, Types\Custom\InputFile|string $livePhoto, Types\Custom\InputFile|string $photo, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendAudio(int|string $chatId, Types\Custom\InputFile|string $audio, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?string $performer = null, ?string $title = null, Types\Custom\InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendDocument(int|string $chatId, Types\Custom\InputFile|string $document, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, Types\Custom\InputFile|string|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $disableContentTypeDetection = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendVideo(int|string $chatId, Types\Custom\InputFile|string $video, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, Types\Custom\InputFile|string|null $thumbnail = null, Types\Custom\InputFile|string|null $cover = null, ?int $startTimestamp = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $supportsStreaming = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendAnimation(int|string $chatId, Types\Custom\InputFile|string $animation, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, Types\Custom\InputFile|string|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendVoice(int|string $chatId, Types\Custom\InputFile|string $voice, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendVideoNote(int|string $chatId, Types\Custom\InputFile|string $videoNote, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $length = null, Types\Custom\InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendPaidMedia(int|string $chatId, int $starCount, array $media, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $payload = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message[] sendMediaGroup(int|string $chatId, array $media, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null)
 * @method Types\Message sendLocation(int|string $chatId, float $latitude, float $longitude, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?float $horizontalAccuracy = null, ?int $livePeriod = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendVenue(int|string $chatId, float $latitude, float $longitude, string $title, string $address, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $foursquareId = null, ?string $foursquareType = null, ?string $googlePlaceId = null, ?string $googlePlaceType = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendContact(int|string $chatId, string $phoneNumber, string $firstName, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $lastName = null, ?string $vcard = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendPoll(int|string $chatId, string $question, array $options, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $questionParseMode = null, ?array $questionEntities = null, ?bool $isAnonymous = null, ?string $type = null, ?bool $allowsMultipleAnswers = null, ?bool $allowsRevoting = null, ?bool $shuffleOptions = null, ?bool $allowAddingOptions = null, ?bool $hideResultsUntilCloses = null, ?bool $membersOnly = null, ?array $countryCodes = null, ?array $correctOptionIds = null, ?string $explanation = null, ?string $explanationParseMode = null, ?array $explanationEntities = null, ?InputPollMedia $explanationMedia = null, ?int $openPeriod = null, ?int $closeDate = null, ?bool $isClosed = null, ?string $description = null, ?string $descriptionParseMode = null, ?array $descriptionEntities = null, ?InputPollMedia $media = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Message sendChecklist(string $businessConnectionId, int|string $chatId, InputChecklist $checklist, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message sendDice(int|string $chatId, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Type sendMessageDraft(int $chatId, int $draftId, ?int $messageThreadId = null, ?string $text = null, ?string $parseMode = null, ?array $entities = null, ?bool $canStop = null, ?bool $keepOnStop = null)
 * @method Types\Type sendChatAction(int|string $chatId, string $action, ?string $businessConnectionId = null, ?int $messageThreadId = null)
 * @method Types\Type setMessageReaction(int|string $chatId, int $messageId, ?array $reaction = null, ?bool $isBig = null)
 * @method Types\UserProfilePhotos getUserProfilePhotos(int $userId, ?int $offset = null, ?int $limit = null)
 * @method Types\UserProfileAudios getUserProfileAudios(int $userId, ?int $offset = null, ?int $limit = null)
 * @method Types\Type setUserEmojiStatus(int $userId, ?string $emojiStatusCustomEmojiId = null, ?int $emojiStatusExpirationDate = null)
 * @method Types\File getFile(string $fileId)
 * @method Types\Type banChatMember(int|string $chatId, int $userId, ?int $untilDate = null, ?bool $revokeMessages = null)
 * @method Types\Type unbanChatMember(int|string $chatId, int $userId, ?bool $onlyIfBanned = null)
 * @method Types\Type restrictChatMember(int|string $chatId, int $userId, ChatPermissions $permissions, ?bool $useIndependentChatPermissions = null, ?int $untilDate = null)
 * @method Types\Type promoteChatMember(int|string $chatId, int $userId, ?bool $isAnonymous = null, ?bool $canManageChat = null, ?bool $canDeleteMessages = null, ?bool $canManageVideoChats = null, ?bool $canRestrictMembers = null, ?bool $canPromoteMembers = null, ?bool $canChangeInfo = null, ?bool $canInviteUsers = null, ?bool $canPostStories = null, ?bool $canEditStories = null, ?bool $canDeleteStories = null, ?bool $canPostMessages = null, ?bool $canEditMessages = null, ?bool $canPinMessages = null, ?bool $canManageTopics = null, ?bool $canManageDirectMessages = null, ?bool $canManageTags = null, ?bool $canSendWelcomeMessages = null)
 * @method Types\Type setChatAdministratorCustomTitle(int|string $chatId, int $userId, string $customTitle)
 * @method Types\Type setChatMemberTag(int|string $chatId, int $userId, ?string $tag = null)
 * @method Types\Type banChatSenderChat(int|string $chatId, int $senderChatId)
 * @method Types\Type unbanChatSenderChat(int|string $chatId, int $senderChatId)
 * @method Types\Type setChatPermissions(int|string $chatId, ChatPermissions $permissions, ?bool $useIndependentChatPermissions = null)
 * @method Types\Type exportChatInviteLink(int|string $chatId)
 * @method Types\ChatInviteLink createChatInviteLink(int|string $chatId, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null)
 * @method Types\ChatInviteLink editChatInviteLink(int|string $chatId, string $inviteLink, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null)
 * @method Types\ChatInviteLink createChatSubscriptionInviteLink(int|string $chatId, int $subscriptionPeriod, int $subscriptionPrice, ?string $name = null)
 * @method Types\ChatInviteLink editChatSubscriptionInviteLink(int|string $chatId, string $inviteLink, ?string $name = null)
 * @method Types\ChatInviteLink revokeChatInviteLink(int|string $chatId, string $inviteLink)
 * @method Types\Type approveChatJoinRequest(int|string $chatId, int $userId)
 * @method Types\Type declineChatJoinRequest(int|string $chatId, int $userId)
 * @method Types\Type answerChatJoinRequestQuery(string $chatJoinRequestQueryId, string $result)
 * @method Types\Type sendChatJoinRequestWebApp(string $chatJoinRequestQueryId, string $webAppUrl)
 * @method Types\Type setChatPhoto(int|string $chatId, Types\Custom\InputFile $photo)
 * @method Types\Type deleteChatPhoto(int|string $chatId)
 * @method Types\Type setChatTitle(int|string $chatId, string $title)
 * @method Types\Type setChatDescription(int|string $chatId, ?string $description = null)
 * @method Types\Type pinChatMessage(int|string $chatId, int $messageId, ?string $businessConnectionId = null, ?bool $disableNotification = null)
 * @method Types\Type unpinChatMessage(int|string $chatId, ?string $businessConnectionId = null, ?int $messageId = null)
 * @method Types\Type unpinAllChatMessages(int|string $chatId)
 * @method Types\Type leaveChat(int|string $chatId)
 * @method Types\ChatFullInfo getChat(int|string $chatId)
 * @method Types\ChatMember[] getChatAdministrators(int|string $chatId, ?bool $returnBots = null)
 * @method Types\Type getChatMemberCount(int|string $chatId)
 * @method Types\ChatMember getChatMember(int|string $chatId, int $userId)
 * @method Types\Message[] getUserPersonalChatMessages(int $userId, int $limit)
 * @method Types\Type setChatStickerSet(int|string $chatId, string $stickerSetName)
 * @method Types\Type deleteChatStickerSet(int|string $chatId)
 * @method Types\Sticker[] getForumTopicIconStickers()
 * @method Types\ForumTopic createForumTopic(int|string $chatId, string $name, ?int $iconColor = null, ?string $iconCustomEmojiId = null)
 * @method Types\Type editForumTopic(int|string $chatId, int $messageThreadId, ?string $name = null, ?string $iconCustomEmojiId = null)
 * @method Types\Type closeForumTopic(int|string $chatId, int $messageThreadId)
 * @method Types\Type reopenForumTopic(int|string $chatId, int $messageThreadId)
 * @method Types\Type deleteForumTopic(int|string $chatId, int $messageThreadId)
 * @method Types\Type unpinAllForumTopicMessages(int|string $chatId, int $messageThreadId)
 * @method Types\Type editGeneralForumTopic(int|string $chatId, string $name)
 * @method Types\Type closeGeneralForumTopic(int|string $chatId)
 * @method Types\Type reopenGeneralForumTopic(int|string $chatId)
 * @method Types\Type hideGeneralForumTopic(int|string $chatId)
 * @method Types\Type unhideGeneralForumTopic(int|string $chatId)
 * @method Types\Type unpinAllGeneralForumTopicMessages(int|string $chatId)
 * @method Types\Type answerCallbackQuery(string $callbackQueryId, ?string $text = null, ?bool $showAlert = null, ?string $url = null, ?int $cacheTime = null)
 * @method Types\SentGuestMessage answerGuestQuery(string $guestQueryId, InlineQueryResult $result)
 * @method Types\UserChatBoosts getUserChatBoosts(int|string $chatId, int $userId)
 * @method Types\BusinessConnection getBusinessConnection(string $businessConnectionId)
 * @method Types\Type getManagedBotToken(int $userId)
 * @method Types\Type replaceManagedBotToken(int $userId)
 * @method Types\BotAccessSettings getManagedBotAccessSettings(int $userId)
 * @method Types\Type setManagedBotAccessSettings(int $userId, bool $isAccessRestricted, ?array $addedUserIds = null)
 * @method Types\Type setMyCommands(array $commands, ?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method Types\Type deleteMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method Types\BotCommand[] getMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method Types\Type setMyName(?string $name = null, ?string $languageCode = null)
 * @method Types\BotName getMyName(?string $languageCode = null)
 * @method Types\Type setMyDescription(?string $description = null, ?string $languageCode = null)
 * @method Types\BotDescription getMyDescription(?string $languageCode = null)
 * @method Types\Type setMyShortDescription(?string $shortDescription = null, ?string $languageCode = null)
 * @method Types\BotShortDescription getMyShortDescription(?string $languageCode = null)
 * @method Types\Type setMyProfilePhoto(InputProfilePhoto $photo)
 * @method Types\Type removeMyProfilePhoto()
 * @method Types\Type setChatMenuButton(?int $chatId = null, ?MenuButton $menuButton = null)
 * @method Types\MenuButton getChatMenuButton(?int $chatId = null)
 * @method Types\Type setMyDefaultAdministratorRights(?ChatAdministratorRights $rights = null, ?bool $forChannels = null)
 * @method Types\ChatAdministratorRights getMyDefaultAdministratorRights(?bool $forChannels = null)
 * @method Types\Gifts getAvailableGifts()
 * @method Types\Type sendGift(string $giftId, ?int $userId = null, int|string|null $chatId = null, ?bool $payForUpgrade = null, ?string $text = null, ?string $textParseMode = null, ?array $textEntities = null)
 * @method Types\Type giftPremiumSubscription(int $userId, int $monthCount, int $starCount, ?string $text = null, ?string $textParseMode = null, ?array $textEntities = null)
 * @method Types\Type verifyUser(int $userId, ?string $customDescription = null)
 * @method Types\Type verifyChat(int|string $chatId, ?string $customDescription = null)
 * @method Types\Type removeUserVerification(int $userId)
 * @method Types\Type removeChatVerification(int|string $chatId)
 * @method Types\Type readBusinessMessage(string $businessConnectionId, int $chatId, int $messageId)
 * @method Types\Type deleteBusinessMessages(string $businessConnectionId, array $messageIds)
 * @method Types\Type setBusinessAccountName(string $businessConnectionId, string $firstName, ?string $lastName = null)
 * @method Types\Type setBusinessAccountUsername(string $businessConnectionId, ?string $username = null)
 * @method Types\Type setBusinessAccountBio(string $businessConnectionId, ?string $bio = null)
 * @method Types\Type setBusinessAccountProfilePhoto(string $businessConnectionId, InputProfilePhoto $photo, ?bool $isPublic = null)
 * @method Types\Type removeBusinessAccountProfilePhoto(string $businessConnectionId, ?bool $isPublic = null)
 * @method Types\Type setBusinessAccountGiftSettings(string $businessConnectionId, bool $showGiftButton, AcceptedGiftTypes $acceptedGiftTypes)
 * @method Types\StarAmount getBusinessAccountStarBalance(string $businessConnectionId)
 * @method Types\Type transferBusinessAccountStars(string $businessConnectionId, int $starCount)
 * @method Types\OwnedGifts getBusinessAccountGifts(string $businessConnectionId, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeUnique = null, ?bool $excludeFromBlockchain = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null)
 * @method Types\OwnedGifts getUserGifts(int $userId, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null)
 * @method Types\OwnedGifts getChatGifts(int|string $chatId, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null)
 * @method Types\Type convertGiftToStars(string $businessConnectionId, string $ownedGiftId)
 * @method Types\Type upgradeGift(string $businessConnectionId, string $ownedGiftId, ?bool $keepOriginalDetails = null, ?int $starCount = null)
 * @method Types\Type transferGift(string $businessConnectionId, string $ownedGiftId, int $newOwnerChatId, ?int $starCount = null)
 * @method Types\Story postStory(string $businessConnectionId, InputStoryContent $content, int $activePeriod, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?array $areas = null, ?bool $postToChatPage = null, ?bool $protectContent = null)
 * @method Types\Story repostStory(string $businessConnectionId, int $fromChatId, int $fromStoryId, int $activePeriod, ?bool $postToChatPage = null, ?bool $protectContent = null)
 * @method Types\Story editStory(string $businessConnectionId, int $storyId, InputStoryContent $content, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?array $areas = null)
 * @method Types\Type deleteStory(string $businessConnectionId, int $storyId)
 * @method Types\SentWebAppMessage answerWebAppQuery(string $webAppQueryId, InlineQueryResult $result)
 * @method Types\PreparedInlineMessage savePreparedInlineMessage(int $userId, InlineQueryResult $result, ?bool $allowUserChats = null, ?bool $allowBotChats = null, ?bool $allowGroupChats = null, ?bool $allowChannelChats = null)
 * @method Types\PreparedKeyboardButton savePreparedKeyboardButton(int $userId, KeyboardButton $button)
 * @method Types\Message editMessageText(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $text = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InputRichMessage $richMessage = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message editMessageCaption(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message editMessageMedia(InputMedia $media, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message editMessageLiveLocation(float $latitude, float $longitude, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?int $livePeriod = null, ?float $horizontalAccuracy = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message stopMessageLiveLocation(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message editMessageChecklist(string $businessConnectionId, int|string $chatId, int $messageId, InputChecklist $checklist, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message editMessageReplyMarkup(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Poll stopPoll(int|string $chatId, int $messageId, ?string $businessConnectionId = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Type editEphemeralMessageText(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?string $text = null, ?string $parseMode = null, ?array $entities = null, ?InputRichMessage $richMessage = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Type editEphemeralMessageMedia(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, InputMedia $media, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Type editEphemeralMessageCaption(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Type editEphemeralMessageReplyMarkup(int|string $chatId, int $receiverUserId, int $ephemeralMessageId, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Type approveSuggestedPost(int $chatId, int $messageId, ?int $sendDate = null)
 * @method Types\Type declineSuggestedPost(int $chatId, int $messageId, ?string $comment = null)
 * @method Types\Type deleteMessage(int|string $chatId, int $messageId)
 * @method Types\Type deleteMessages(int|string $chatId, array $messageIds)
 * @method Types\Type deleteEphemeralMessage(int|string $chatId, int $receiverUserId, int $ephemeralMessageId)
 * @method Types\Type deleteMessageReaction(int|string $chatId, int $messageId, ?int $userId = null, ?int $actorChatId = null)
 * @method Types\Type deleteAllMessageReactions(int|string $chatId, ?int $userId = null, ?int $actorChatId = null)
 * @method Types\Message sendSticker(int|string $chatId, Types\Custom\InputFile|string $sticker, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\StickerSet getStickerSet(string $name)
 * @method Types\Sticker[] getCustomEmojiStickers(array $customEmojiIds)
 * @method Types\File uploadStickerFile(int $userId, Types\Custom\InputFile $sticker, string $stickerFormat)
 * @method Types\Type createNewStickerSet(int $userId, string $name, string $title, array $stickers, ?string $stickerType = null, ?bool $needsRepainting = null)
 * @method Types\Type addStickerToSet(int $userId, string $name, InputSticker $sticker)
 * @method Types\Type setStickerPositionInSet(string $sticker, int $position)
 * @method Types\Type deleteStickerFromSet(string $sticker)
 * @method Types\Type replaceStickerInSet(int $userId, string $name, string $oldSticker, InputSticker $sticker)
 * @method Types\Type setStickerEmojiList(string $sticker, array $emojiList)
 * @method Types\Type setStickerKeywords(string $sticker, ?array $keywords = null)
 * @method Types\Type setStickerMaskPosition(string $sticker, ?MaskPosition $maskPosition = null)
 * @method Types\Type setStickerSetTitle(string $name, string $title)
 * @method Types\Type setStickerSetThumbnail(string $name, int $userId, string $format, Types\Custom\InputFile|string|null $thumbnail = null)
 * @method Types\Type setCustomEmojiStickerSetThumbnail(string $name, ?string $customEmojiId = null)
 * @method Types\Type deleteStickerSet(string $name)
 * @method Types\Message sendRichMessage(int|string $chatId, InputRichMessage $richMessage, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null)
 * @method Types\Type sendRichMessageDraft(int $chatId, int $draftId, InputRichMessage $richMessage, ?int $messageThreadId = null, ?bool $canStop = null, ?bool $keepOnStop = null)
 * @method Types\Type answerInlineQuery(string $inlineQueryId, array $results, ?int $cacheTime = null, ?bool $isPersonal = null, ?string $nextOffset = null, ?InlineQueryResultsButton $button = null)
 * @method Types\Message sendInvoice(int|string $chatId, string $title, string $description, string $payload, string $currency, array $prices, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $providerToken = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $startParameter = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Type createInvoiceLink(string $title, string $description, string $payload, string $currency, array $prices, ?string $businessConnectionId = null, ?string $providerToken = null, ?int $subscriptionPeriod = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null)
 * @method Types\Type answerShippingQuery(string $shippingQueryId, bool $ok, ?array $shippingOptions = null, ?string $errorMessage = null)
 * @method Types\Type answerPreCheckoutQuery(string $preCheckoutQueryId, bool $ok, ?string $errorMessage = null)
 * @method Types\StarAmount getMyStarBalance()
 * @method Types\StarTransactions getStarTransactions(?int $offset = null, ?int $limit = null)
 * @method Types\Type refundStarPayment(int $userId, string $telegramPaymentChargeId)
 * @method Types\Type editUserStarSubscription(int $userId, string $telegramPaymentChargeId, bool $isCanceled)
 * @method Types\Type setPassportDataErrors(int $userId, array $errors)
 * @method Types\Message sendGame(int|string $chatId, string $gameShortName, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null)
 * @method Types\Message setGameScore(int $userId, int $score, ?bool $force = null, ?bool $disableEditMessage = null, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null)
 * @method Types\GameHighScore[] getGameHighScores(int $userId, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null)
 */
class Telegram
{
    private Config $config;
    private HttpClientInterface $httpClient;
    private Pipeline $pipeline;

    public function __construct(string|Config $tokenOrConfig)
    {
        if (is_string($tokenOrConfig)) {
            $this->config = new Config(botToken: $tokenOrConfig);
        } else {
            $this->config = $tokenOrConfig;
        }

        $this->httpClient = $this->config->httpClient ?? new GuzzleHttpClient();
        $this->pipeline = new Pipeline();

        // Default middlewares
        $this->pipeline->pipe(new RetryMiddleware($this->config->retryCount));
        if ($this->config->logger !== null) {
            $this->pipeline->pipe(new LoggingMiddleware($this->config->logger));
        }
    }

    /**
     * Fluent factory builder.
     */
    public static function create(string $botToken): ConfigBuilder
    {
        return new ConfigBuilder($botToken);
    }

    /**
     * Appends a middleware to the execution pipeline.
     */
    public function pipe(MiddlewareInterface|Closure $middleware): static
    {
        $this->pipeline->pipe($middleware);
        return $this;
    }

    /**
     * Sends a Method object to the Telegram Bot API.
     */
    public function send(Method $method, ?Closure $uploadProgress = null, ?Closure $downloadProgress = null): mixed
    {
        [$params, $files] = $method->buildRequestData();

        $request = new Request(
            endpoint: $method->getEndpoint(),
            parameters: $params,
            files: $files,
            httpMethod: $method->getHttpMethod(),
            uploadProgress: $uploadProgress ?? $this->config->uploadProgress,
            downloadProgress: $downloadProgress ?? $this->config->downloadProgress
        );

        $response = $this->pipeline->run(
            $request,
            $this->config,
            fn(Request $req, Config $cfg): Response => $this->httpClient->send($cfg, $req)
        );

        if (!$response->isOk()) {
            throw ApiException::fromResponse($response->data);
        }

        $result = $response->getResult();
        $returnInfo = $method->getReturnTypeInfo();

        return $this->unwrapResult($result, $returnInfo?->type, $returnInfo?->isArray ?? false);
    }

    /**
     * Dynamic method invocation for any Telegram Bot API method.
     *
     * Example:
     * $telegram->sendMessage(chatId: 123456, text: 'Hello, Queen!');
     */
    public function __call(string $name, array $arguments): mixed
    {
        $className = 'Tueen\\Telegram\\Methods\\' . ucfirst($name);

        if (class_exists($className)) {
            $methodInstance = $this->instantiateMethod($className, $arguments);
            return $this->send($methodInstance);
        }

        // Dynamic fallback: build a dynamic Method instance
        $dynamicMethod = new class($name, $arguments) extends Method {
            public function __construct(
                private readonly string $endpointName,
                array $args
            ) {
                // If single associative array passed, or named args
                if (count($args) === 1 && isset($args[0]) && is_array($args[0])) {
                    $this->parameters = $args[0];
                } else {
                    foreach ($args as $k => $v) {
                        $this->parameters[Type::toSnakeCase((string)$k)] = $v;
                    }
                }
            }

            public function getEndpoint(): string
            {
                return $this->endpointName;
            }
        };

        return $this->send($dynamicMethod);
    }

    /**
     * Dynamically instantiates a Method class matching named or positional arguments.
     */
    private function instantiateMethod(string $className, array $arguments): Method
    {
        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if ($constructor === null || empty($arguments)) {
            return $reflection->newInstance();
        }

        // If a single associative array is provided, e.g. $telegram->sendMessage([...])
        if (count($arguments) === 1 && isset($arguments[0]) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        $parameters = $constructor->getParameters();
        $passedArgs = [];

        foreach ($parameters as $param) {
            $pName = $param->getName();
            $snake = Type::toSnakeCase($pName);

            if (array_key_exists($pName, $arguments)) {
                $passedArgs[$pName] = $arguments[$pName];
            } elseif (array_key_exists($snake, $arguments)) {
                $passedArgs[$pName] = $arguments[$snake];
            } elseif ($param->isDefaultValueAvailable()) {
                $passedArgs[$pName] = $param->getDefaultValue();
            }
        }

        return $reflection->newInstanceArgs($passedArgs);
    }

    /**
     * Unwraps and deserializes API response result.
     */
    private function unwrapResult(mixed $result, ?string $expectedType = null, bool $isArray = false): mixed
    {
        if ($result === null || is_scalar($result)) {
            return $result;
        }

        if ($isArray && is_array($result)) {
            $targetClass = $expectedType ?? Type::class;
            return Type::castArrayOf($result, $targetClass);
        }

        if (is_array($result)) {
            $targetClass = $expectedType ?? Type::class;
            return Type::factory($targetClass, $result);
        }

        return $result;
    }

    /**
     * Downloads a file from Telegram.
     *
     * @param string|File $file File ID, file path, or File type instance
     * @param resource|string $destination Target local path or stream resource
     * @param callable|null $progress fn(int $downloadedBytes, int $totalBytes, float $percentage)
     */
    public function downloadFile(mixed $file, mixed $destination, ?callable $progress = null): bool
    {
        $filePath = null;

        if ($file instanceof File) {
            $filePath = $file->filePath;
        } elseif (is_string($file)) {
            // Check if it's already a relative path with extension
            if (str_contains($file, '/') || str_contains($file, '.')) {
                $filePath = $file;
            } else {
                // It's a file_id, retrieve File object first
                /** @var File $fileObj */
                $fileObj = $this->getFile(fileId: $file);
                $filePath = $fileObj->filePath;
            }
        }

        if (empty($filePath)) {
            throw new TelegramException("Unable to resolve file path for download.");
        }

        return $this->httpClient->download($this->config, $filePath, $destination, $progress);
    }

    /**
     * Helper to parse and handle incoming webhook requests.
     */
    public function handleWebhook(?string $rawInput = null): Update
    {
        if ($rawInput === null) {
            $rawInput = file_get_contents('php://input');
        }

        if (empty($rawInput)) {
            throw new TelegramException("Empty webhook payload received.");
        }

        $data = json_decode($rawInput, true);
        if (!is_array($data)) {
            throw new TelegramException("Invalid JSON payload in webhook: " . json_last_error_msg());
        }

        return new Update($data);
    }

    /**
     * Long polling update generator.
     *
     * @return Generator<Update>
     */
    public function poll(int $timeout = 30, int $limit = 100, ?array $allowedUpdates = null): Generator
    {
        $offset = 0;

        while (true) {
            try {
                /** @var Update[] $updates */
                $updates = $this->getUpdates(
                    offset: $offset,
                    limit: $limit,
                    timeout: $timeout,
                    allowedUpdates: $allowedUpdates
                );

                foreach ($updates as $update) {
                    $offset = max($offset, $update->updateId + 1);
                    yield $update;
                }
            } catch (TelegramException $e) {
                // Yield error or backoff
                sleep(2);
            }
        }
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}
