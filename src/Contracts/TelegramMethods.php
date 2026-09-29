<?php

declare(strict_types=1);

namespace Tueen\Telegram\Contracts;

use Tueen\Telegram\Client\RequestOptions;
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
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\BotCantInitiateConversationException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\BusinessConnectionNotFoundException;
use Tueen\Telegram\Exceptions\BusinessConnectionRevokedException;
use Tueen\Telegram\Exceptions\ButtonDataInvalidException;
use Tueen\Telegram\Exceptions\CantParseEntitiesException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\CommandInvalidException;
use Tueen\Telegram\Exceptions\CommandTooLongException;
use Tueen\Telegram\Exceptions\CommandsListEmptyException;
use Tueen\Telegram\Exceptions\DateInPastException;
use Tueen\Telegram\Exceptions\DateTooFarException;
use Tueen\Telegram\Exceptions\FileTooLargeException;
use Tueen\Telegram\Exceptions\InvalidLanguageCodeException;
use Tueen\Telegram\Exceptions\MediaEmptyException;
use Tueen\Telegram\Exceptions\MessageCantBeDeletedException;
use Tueen\Telegram\Exceptions\MessageCantBeEditedException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotModifiedException;
use Tueen\Telegram\Exceptions\MessageTooLongException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\PhotoInvalidDimensionsException;
use Tueen\Telegram\Exceptions\QueryIdInvalidException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\StarsAmountInvalidException;
use Tueen\Telegram\Exceptions\StickerDimensionsInvalidException;
use Tueen\Telegram\Exceptions\StickerEmojiInvalidException;
use Tueen\Telegram\Exceptions\StickerSetInvalidException;
use Tueen\Telegram\Exceptions\TooManyCommandsException;
use Tueen\Telegram\Exceptions\TopicNotModifiedException;
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Exceptions\UserAlreadyParticipantException;
use Tueen\Telegram\Exceptions\UserCantBeVerifiedException;
use Tueen\Telegram\Exceptions\UserNotFoundException;
use Tueen\Telegram\Exceptions\VoiceMessagesForbiddenException;
use Tueen\Telegram\Exceptions\WebhookActiveConflictException;
use Tueen\Telegram\Exceptions\WrongFileTypeException;
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
 * @method ArrayResult<Update>|Error getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowedUpdates = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setWebhook(string $url, ?InputFile $certificate = null, ?string $ipAddress = null, ?int $maxConnections = null, ?array $allowedUpdates = null, ?bool $dropPendingUpdates = null, ?string $secretToken = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteWebhook(?bool $dropPendingUpdates = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method WebhookInfo|Error getWebhookInfo(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method User|Error getMe(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error logOut(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error close(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendMessage(int|string|null $chatId = null, ?string $text = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error forwardMessage(int|string|null $chatId = null, int|string|null $fromChatId = null, ?int $messageId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<MessageId>|Error forwardMessages(int|string|null $chatId = null, int|string|null $fromChatId = null, ?array $messageIds = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method MessageId|Error copyMessage(int|string|null $chatId = null, int|string|null $fromChatId = null, ?int $messageId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?int $videoStartTimestamp = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<MessageId>|Error copyMessages(int|string|null $chatId = null, int|string|null $fromChatId = null, ?array $messageIds = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $removeCaption = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendPhoto(int|string|null $chatId = null, InputFile|string|null $photo = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendLivePhoto(int|string|null $chatId = null, InputFile|string|null $livePhoto = null, InputFile|string|null $photo = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendAudio(int|string|null $chatId = null, InputFile|string|null $audio = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?string $performer = null, ?string $title = null, InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendDocument(int|string|null $chatId = null, InputFile|string|null $document = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $disableContentTypeDetection = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendVideo(int|string|null $chatId = null, InputFile|string|null $video = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, InputFile|string|null $cover = null, ?int $startTimestamp = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $supportsStreaming = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendAnimation(int|string|null $chatId = null, InputFile|string|null $animation = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendVoice(int|string|null $chatId = null, InputFile|string|null $voice = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendVideoNote(int|string|null $chatId = null, InputFile|string|null $videoNote = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?int $duration = null, ?int $length = null, InputFile|string|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendPaidMedia(int|string|null $chatId = null, ?int $starCount = null, ?array $media = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $payload = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<Message>|Error sendMediaGroup(int|string|null $chatId = null, ?array $media = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendLocation(int|string|null $chatId = null, ?float $latitude = null, ?float $longitude = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?float $horizontalAccuracy = null, ?int $livePeriod = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendVenue(int|string|null $chatId = null, ?float $latitude = null, ?float $longitude = null, ?string $title = null, ?string $address = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $foursquareId = null, ?string $foursquareType = null, ?string $googlePlaceId = null, ?string $googlePlaceType = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendContact(int|string|null $chatId = null, ?string $phoneNumber = null, ?string $firstName = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $lastName = null, ?string $vcard = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendPoll(int|string|null $chatId = null, ?string $question = null, ?array $options = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ParseMode|string|null $questionParseMode = null, ?array $questionEntities = null, ?bool $isAnonymous = null, PollType|string|null $type = null, ?bool $allowsMultipleAnswers = null, ?bool $allowsRevoting = null, ?bool $shuffleOptions = null, ?bool $allowAddingOptions = null, ?bool $hideResultsUntilCloses = null, ?bool $membersOnly = null, ?array $countryCodes = null, ?array $correctOptionIds = null, ?string $explanation = null, ParseMode|string|null $explanationParseMode = null, ?array $explanationEntities = null, ?InputPollMedia $explanationMedia = null, ?int $openPeriod = null, ?int $closeDate = null, ?bool $isClosed = null, ?string $description = null, ParseMode|string|null $descriptionParseMode = null, ?array $descriptionEntities = null, ?InputPollMedia $media = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendChecklist(?string $businessConnectionId = null, int|string|null $chatId = null, ?InputChecklist $checklist = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendDice(int|string|null $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, DiceEmoji|string|null $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error sendMessageDraft(?int $chatId = null, ?int $draftId = null, ?int $messageThreadId = null, ?string $text = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?bool $canStop = null, ?bool $keepOnStop = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error sendChatAction(int|string|null $chatId = null, ChatAction|string|null $action = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMessageReaction(int|string|null $chatId = null, ?int $messageId = null, ?array $reaction = null, ?bool $isBig = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method UserProfilePhotos|Error getUserProfilePhotos(?int $userId = null, ?int $offset = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method UserProfileAudios|Error getUserProfileAudios(?int $userId = null, ?int $offset = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setUserEmojiStatus(?int $userId = null, ?string $emojiStatusCustomEmojiId = null, ?int $emojiStatusExpirationDate = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method File|Error getFile(string $fileId, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error banChatMember(int|string|null $chatId = null, ?int $userId = null, ?int $untilDate = null, ?bool $revokeMessages = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unbanChatMember(int|string|null $chatId = null, ?int $userId = null, ?bool $onlyIfBanned = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error restrictChatMember(int|string|null $chatId = null, ?int $userId = null, ?ChatPermissions $permissions = null, ?bool $useIndependentChatPermissions = null, ?int $untilDate = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error promoteChatMember(int|string|null $chatId = null, ?int $userId = null, ?bool $isAnonymous = null, ?bool $canManageChat = null, ?bool $canDeleteMessages = null, ?bool $canManageVideoChats = null, ?bool $canRestrictMembers = null, ?bool $canPromoteMembers = null, ?bool $canChangeInfo = null, ?bool $canInviteUsers = null, ?bool $canPostStories = null, ?bool $canEditStories = null, ?bool $canDeleteStories = null, ?bool $canPostMessages = null, ?bool $canEditMessages = null, ?bool $canPinMessages = null, ?bool $canManageTopics = null, ?bool $canManageDirectMessages = null, ?bool $canManageTags = null, ?bool $canSendWelcomeMessages = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatAdministratorCustomTitle(int|string|null $chatId = null, ?int $userId = null, ?string $customTitle = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatMemberTag(int|string|null $chatId = null, ?int $userId = null, ?string $tag = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error banChatSenderChat(int|string|null $chatId = null, ?int $senderChatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unbanChatSenderChat(int|string|null $chatId = null, ?int $senderChatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatPermissions(int|string|null $chatId = null, ?ChatPermissions $permissions = null, ?bool $useIndependentChatPermissions = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StringResult|Error exportChatInviteLink(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatInviteLink|Error createChatInviteLink(int|string|null $chatId = null, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatInviteLink|Error editChatInviteLink(int|string|null $chatId = null, ?string $inviteLink = null, ?string $name = null, ?int $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatInviteLink|Error createChatSubscriptionInviteLink(int|string|null $chatId = null, ?int $subscriptionPeriod = null, ?int $subscriptionPrice = null, ?string $name = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatInviteLink|Error editChatSubscriptionInviteLink(int|string|null $chatId = null, ?string $inviteLink = null, ?string $name = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatInviteLink|Error revokeChatInviteLink(int|string|null $chatId = null, ?string $inviteLink = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error approveChatJoinRequest(int|string|null $chatId = null, ?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error declineChatJoinRequest(int|string|null $chatId = null, ?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error answerChatJoinRequestQuery(string $chatJoinRequestQueryId, ChatJoinRequestResult|string $result, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error sendChatJoinRequestWebApp(string $chatJoinRequestQueryId, string $webAppUrl, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatPhoto(int|string|null $chatId = null, ?InputFile $photo = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteChatPhoto(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatTitle(int|string|null $chatId = null, ?string $title = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatDescription(int|string|null $chatId = null, ?string $description = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error pinChatMessage(int|string|null $chatId = null, ?int $messageId = null, ?string $businessConnectionId = null, ?bool $disableNotification = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unpinChatMessage(int|string|null $chatId = null, ?string $businessConnectionId = null, ?int $messageId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unpinAllChatMessages(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error leaveChat(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatFullInfo|Error getChat(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<ChatMember>|Error getChatAdministrators(int|string|null $chatId = null, ?bool $returnBots = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method IntegerResult|Error getChatMemberCount(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatMember|Error getChatMember(int|string|null $chatId = null, ?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<Message>|Error getUserPersonalChatMessages(?int $userId = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatStickerSet(int|string|null $chatId = null, ?string $stickerSetName = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteChatStickerSet(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<Sticker>|Error getForumTopicIconStickers(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ForumTopic|Error createForumTopic(int|string|null $chatId = null, ?string $name = null, ForumIconColor|int|null $iconColor = null, ?string $iconCustomEmojiId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editForumTopic(int|string|null $chatId = null, ?int $messageThreadId = null, ?string $name = null, ?string $iconCustomEmojiId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error closeForumTopic(int|string|null $chatId = null, ?int $messageThreadId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error reopenForumTopic(int|string|null $chatId = null, ?int $messageThreadId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteForumTopic(int|string|null $chatId = null, ?int $messageThreadId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unpinAllForumTopicMessages(int|string|null $chatId = null, ?int $messageThreadId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editGeneralForumTopic(int|string|null $chatId = null, ?string $name = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error closeGeneralForumTopic(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error reopenGeneralForumTopic(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error hideGeneralForumTopic(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unhideGeneralForumTopic(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error unpinAllGeneralForumTopicMessages(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error answerCallbackQuery(?string $callbackQueryId = null, ?string $text = null, ?bool $showAlert = null, ?string $url = null, ?int $cacheTime = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method SentGuestMessage|Error answerGuestQuery(?string $guestQueryId = null, ?InlineQueryResult $result = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method UserChatBoosts|Error getUserChatBoosts(int|string|null $chatId = null, ?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BusinessConnection|Error getBusinessConnection(?string $businessConnectionId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StringResult|Error getManagedBotToken(?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StringResult|Error replaceManagedBotToken(?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BotAccessSettings|Error getManagedBotAccessSettings(?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setManagedBotAccessSettings(?int $userId = null, ?bool $isAccessRestricted = null, ?array $addedUserIds = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMyCommands(array $commands, ?BotCommandScope $scope = null, ?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<BotCommand>|Error getMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMyName(?string $name = null, ?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BotName|Error getMyName(?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMyDescription(?string $description = null, ?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BotDescription|Error getMyDescription(?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMyShortDescription(?string $shortDescription = null, ?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BotShortDescription|Error getMyShortDescription(?string $languageCode = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMyProfilePhoto(InputProfilePhoto $photo, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error removeMyProfilePhoto(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setChatMenuButton(?int $chatId = null, ?MenuButton $menuButton = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method MenuButton|Error getChatMenuButton(?int $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setMyDefaultAdministratorRights(?ChatAdministratorRights $rights = null, ?bool $forChannels = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ChatAdministratorRights|Error getMyDefaultAdministratorRights(?bool $forChannels = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Gifts|Error getAvailableGifts(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error sendGift(string $giftId, ?int $userId = null, int|string|null $chatId = null, ?bool $payForUpgrade = null, ?string $text = null, ParseMode|string|null $textParseMode = null, ?array $textEntities = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error giftPremiumSubscription(?int $userId = null, ?int $monthCount = null, ?int $starCount = null, ?string $text = null, ParseMode|string|null $textParseMode = null, ?array $textEntities = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error verifyUser(?int $userId = null, ?string $customDescription = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error verifyChat(int|string|null $chatId = null, ?string $customDescription = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error removeUserVerification(?int $userId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error removeChatVerification(int|string|null $chatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error readBusinessMessage(?string $businessConnectionId = null, ?int $chatId = null, ?int $messageId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteBusinessMessages(?string $businessConnectionId = null, ?array $messageIds = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountName(?string $businessConnectionId = null, ?string $firstName = null, ?string $lastName = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountUsername(?string $businessConnectionId = null, ?string $username = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountBio(?string $businessConnectionId = null, ?string $bio = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountProfilePhoto(?string $businessConnectionId = null, ?InputProfilePhoto $photo = null, ?bool $isPublic = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error removeBusinessAccountProfilePhoto(?string $businessConnectionId = null, ?bool $isPublic = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setBusinessAccountGiftSettings(?string $businessConnectionId = null, ?bool $showGiftButton = null, ?AcceptedGiftTypes $acceptedGiftTypes = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StarAmount|Error getBusinessAccountStarBalance(?string $businessConnectionId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error transferBusinessAccountStars(?string $businessConnectionId = null, ?int $starCount = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method OwnedGifts|Error getBusinessAccountGifts(?string $businessConnectionId = null, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeUnique = null, ?bool $excludeFromBlockchain = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method OwnedGifts|Error getUserGifts(?int $userId = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method OwnedGifts|Error getChatGifts(int|string|null $chatId = null, ?bool $excludeUnsaved = null, ?bool $excludeSaved = null, ?bool $excludeUnlimited = null, ?bool $excludeLimitedUpgradable = null, ?bool $excludeLimitedNonUpgradable = null, ?bool $excludeFromBlockchain = null, ?bool $excludeUnique = null, ?bool $sortByPrice = null, ?string $offset = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error convertGiftToStars(?string $businessConnectionId = null, ?string $ownedGiftId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error upgradeGift(?string $businessConnectionId = null, ?string $ownedGiftId = null, ?bool $keepOriginalDetails = null, ?int $starCount = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error transferGift(?string $businessConnectionId = null, ?string $ownedGiftId = null, ?int $newOwnerChatId = null, ?int $starCount = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Story|Error postStory(?string $businessConnectionId = null, ?InputStoryContent $content = null, StoryActivePeriod|int|null $activePeriod = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?array $areas = null, ?bool $postToChatPage = null, ?bool $protectContent = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Story|Error repostStory(?string $businessConnectionId = null, ?int $fromChatId = null, ?int $fromStoryId = null, StoryActivePeriod|int|null $activePeriod = null, ?bool $postToChatPage = null, ?bool $protectContent = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Story|Error editStory(?string $businessConnectionId = null, ?int $storyId = null, ?InputStoryContent $content = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?array $areas = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteStory(?string $businessConnectionId = null, ?int $storyId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method SentWebAppMessage|Error answerWebAppQuery(string $webAppQueryId, InlineQueryResult $result, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method PreparedInlineMessage|Error savePreparedInlineMessage(?int $userId = null, ?InlineQueryResult $result = null, ?bool $allowUserChats = null, ?bool $allowBotChats = null, ?bool $allowGroupChats = null, ?bool $allowChannelChats = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method PreparedKeyboardButton|Error savePreparedKeyboardButton(?int $userId = null, ?KeyboardButton $button = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageText(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $text = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InputRichMessage $richMessage = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageCaption(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageMedia(InputMedia $media, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageLiveLocation(float $latitude, float $longitude, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?int $livePeriod = null, ?float $horizontalAccuracy = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error stopMessageLiveLocation(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error editMessageChecklist(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?InputChecklist $checklist = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error editMessageReplyMarkup(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Poll|Error stopPoll(int|string|null $chatId = null, ?int $messageId = null, ?string $businessConnectionId = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageText(int|string|null $chatId = null, ?int $receiverUserId = null, ?int $ephemeralMessageId = null, ?string $text = null, ParseMode|string|null $parseMode = null, ?array $entities = null, ?InputRichMessage $richMessage = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageMedia(int|string|null $chatId = null, ?int $receiverUserId = null, ?int $ephemeralMessageId = null, ?InputMedia $media = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageCaption(int|string|null $chatId = null, ?int $receiverUserId = null, ?int $ephemeralMessageId = null, ?string $caption = null, ParseMode|string|null $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editEphemeralMessageReplyMarkup(int|string|null $chatId = null, ?int $receiverUserId = null, ?int $ephemeralMessageId = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error approveSuggestedPost(?int $chatId = null, ?int $messageId = null, ?int $sendDate = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error declineSuggestedPost(?int $chatId = null, ?int $messageId = null, ?string $comment = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteMessage(int|string|null $chatId = null, ?int $messageId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteMessages(int|string|null $chatId = null, ?array $messageIds = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteEphemeralMessage(int|string|null $chatId = null, ?int $receiverUserId = null, ?int $ephemeralMessageId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteMessageReaction(int|string|null $chatId = null, ?int $messageId = null, ?int $userId = null, ?int $actorChatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteAllMessageReactions(int|string|null $chatId = null, ?int $userId = null, ?int $actorChatId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendSticker(int|string|null $chatId = null, InputFile|string|null $sticker = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StickerSet|Error getStickerSet(string $name, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<Sticker>|Error getCustomEmojiStickers(array $customEmojiIds, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method File|Error uploadStickerFile(?int $userId = null, ?InputFile $sticker = null, StickerFormat|string|null $stickerFormat = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error createNewStickerSet(?int $userId = null, ?string $name = null, ?string $title = null, ?array $stickers = null, StickerType|string|null $stickerType = null, ?bool $needsRepainting = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error addStickerToSet(?int $userId = null, ?string $name = null, ?InputSticker $sticker = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerPositionInSet(string $sticker, int $position, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteStickerFromSet(string $sticker, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error replaceStickerInSet(?int $userId = null, ?string $name = null, ?string $oldSticker = null, ?InputSticker $sticker = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerEmojiList(string $sticker, array $emojiList, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerKeywords(string $sticker, ?array $keywords = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerMaskPosition(string $sticker, ?MaskPosition $maskPosition = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerSetTitle(string $name, string $title, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setStickerSetThumbnail(string $name, ?int $userId = null, StickerFormat|string|null $format = null, InputFile|string|null $thumbnail = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setCustomEmojiStickerSetThumbnail(string $name, ?string $customEmojiId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error deleteStickerSet(string $name, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendRichMessage(int|string|null $chatId = null, ?InputRichMessage $richMessage = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?EphemeralMessageParameters $ephemeralMessageParameters = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error sendRichMessageDraft(?int $chatId = null, ?int $draftId = null, ?InputRichMessage $richMessage = null, ?int $messageThreadId = null, ?bool $canStop = null, ?bool $keepOnStop = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error answerInlineQuery(?string $inlineQueryId = null, ?array $results = null, ?int $cacheTime = null, ?bool $isPersonal = null, ?string $nextOffset = null, ?InlineQueryResultsButton $button = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendInvoice(int|string|null $chatId = null, ?string $title = null, ?string $description = null, ?string $payload = null, Currency|string|null $currency = null, ?array $prices = null, ?int $messageThreadId = null, ?int $directMessagesTopicId = null, ?string $providerToken = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $startParameter = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StringResult|Error createInvoiceLink(string $title, string $description, string $payload, Currency|string $currency, array $prices, ?string $businessConnectionId = null, ?string $providerToken = null, ?int $subscriptionPeriod = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error answerShippingQuery(?string $shippingQueryId = null, ?bool $ok = null, ?array $shippingOptions = null, ?string $errorMessage = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error answerPreCheckoutQuery(?string $preCheckoutQueryId = null, ?bool $ok = null, ?string $errorMessage = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StarAmount|Error getMyStarBalance(RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method StarTransactions|Error getStarTransactions(?int $offset = null, ?int $limit = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error refundStarPayment(?int $userId = null, ?string $telegramPaymentChargeId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error editUserStarSubscription(?int $userId = null, ?string $telegramPaymentChargeId = null, ?bool $isCanceled = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method BooleanResult|Error setPassportDataErrors(?int $userId = null, ?array $errors = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|Error sendGame(int|string|null $chatId = null, ?string $gameShortName = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?bool $allowPaidBroadcast = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?InlineKeyboardMarkup $replyMarkup = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method Message|BooleanResult|Error setGameScore(?int $userId = null, ?int $score = null, ?bool $force = null, ?bool $disableEditMessage = null, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 * @method ArrayResult<GameHighScore>|Error getGameHighScores(?int $userId = null, ?int $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, RequestOptions|array|null $_ = null, mixed ...$extra)
 */
abstract class TelegramMethods
{
}
