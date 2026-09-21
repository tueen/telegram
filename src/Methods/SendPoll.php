<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\InputPollMedia;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\ForceReply;

/**
 * Use this method to send a native poll. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendpoll
 */
#[ApiMethod('sendPoll', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendPoll extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username. Polls can't be sent to channel direct messages chats.
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Poll question, 1-300 characters
     */
    #[Field('question', required: true)]
    public string $question;

    /**
     * A JSON-serialized list of 1-12 answer options
     */
    #[Field('options', required: true)]
    public array $options;

    /**
     * Unique identifier of the business connection on behalf of which the message will be sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    /**
     * Mode for parsing entities in the question. See formatting options for more details. Currently, only custom emoji entities are allowed.
     */
    #[Field('question_parse_mode', required: false)]
    public ?string $questionParseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the poll question. It can be specified instead of question_parse_mode.
     */
    #[Field('question_entities', required: false)]
    public ?array $questionEntities = null;

    /**
     * True, if the poll needs to be anonymous, defaults to True
     */
    #[Field('is_anonymous', required: false)]
    public ?bool $isAnonymous = null;

    /**
     * Poll type, "quiz" or "regular", defaults to "regular"
     */
    #[Field('type', required: false)]
    public ?string $type = null;

    /**
     * Pass True if the poll allows multiple answers, defaults to False
     */
    #[Field('allows_multiple_answers', required: false)]
    public ?bool $allowsMultipleAnswers = null;

    /**
     * Pass True if the poll allows to change chosen answer options, defaults to False for quizzes and to True for regular polls
     */
    #[Field('allows_revoting', required: false)]
    public ?bool $allowsRevoting = null;

    /**
     * Pass True if the poll options must be shown in random order
     */
    #[Field('shuffle_options', required: false)]
    public ?bool $shuffleOptions = null;

    /**
     * Pass True if answer options can be added to the poll after creation; not supported for anonymous polls and quizzes
     */
    #[Field('allow_adding_options', required: false)]
    public ?bool $allowAddingOptions = null;

    /**
     * Pass True if poll results must be shown only after the poll closes
     */
    #[Field('hide_results_until_closes', required: false)]
    public ?bool $hideResultsUntilCloses = null;

    /**
     * Pass True if voting is limited to users who have been members of the chat where the poll is being sent for more than 24 hours; for channel chats only
     */
    #[Field('members_only', required: false)]
    public ?bool $membersOnly = null;

    /**
     * A JSON-serialized list of 0-12 two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which users can vote in the poll; for channel chats only. Use "FT" as a country code to allow users with anonymous numbers to vote. If omitted or empty, then users from any country can participate in the poll.
     */
    #[Field('country_codes', required: false)]
    public ?array $countryCodes = null;

    /**
     * A JSON-serialized list of monotonically increasing 0-based identifiers of the correct answer options, required for polls in quiz mode
     */
    #[Field('correct_option_ids', required: false)]
    public ?array $correctOptionIds = null;

    /**
     * Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters with at most 2 line feeds after entities parsing
     */
    #[Field('explanation', required: false)]
    public ?string $explanation = null;

    /**
     * Mode for parsing entities in the explanation. See formatting options for more details.
     */
    #[Field('explanation_parse_mode', required: false)]
    public ?string $explanationParseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the poll explanation. It can be specified instead of explanation_parse_mode.
     */
    #[Field('explanation_entities', required: false)]
    public ?array $explanationEntities = null;

    /**
     * Media added to the quiz explanation
     */
    #[Field('explanation_media', required: false)]
    public ?InputPollMedia $explanationMedia = null;

    /**
     * Amount of time in seconds the poll will be active after creation, 5-2628000. Can't be used together with close_date.
     */
    #[Field('open_period', required: false)]
    public ?int $openPeriod = null;

    /**
     * Point in time (Unix timestamp) when the poll will be automatically closed. Must be at least 5 and no more than 2628000 seconds in the future. Can't be used together with open_period.
     */
    #[Field('close_date', required: false)]
    public ?int $closeDate = null;

    /**
     * Pass True if the poll needs to be immediately closed. This can be useful for poll preview.
     */
    #[Field('is_closed', required: false)]
    public ?bool $isClosed = null;

    /**
     * Description of the poll to be sent, 0-1024 characters after entities parsing
     */
    #[Field('description', required: false)]
    public ?string $description = null;

    /**
     * Mode for parsing entities in the poll description. See formatting options for more details.
     */
    #[Field('description_parse_mode', required: false)]
    public ?string $descriptionParseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the poll description, which can be specified instead of description_parse_mode
     */
    #[Field('description_entities', required: false)]
    public ?array $descriptionEntities = null;

    /**
     * Media added to the poll description
     */
    #[Field('media', required: false)]
    public ?InputPollMedia $media = null;

    /**
     * Sends the message silently. Users will receive a notification with no sound.
     */
    #[Field('disable_notification', required: false)]
    public ?bool $disableNotification = null;

    /**
     * Protects the contents of the sent message from forwarding and saving
     */
    #[Field('protect_content', required: false)]
    public ?bool $protectContent = null;

    /**
     * Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     */
    #[Field('allow_paid_broadcast', required: false)]
    public ?bool $allowPaidBroadcast = null;

    /**
     * Unique identifier of the message effect to be added to the message; for private chats only
     */
    #[Field('message_effect_id', required: false)]
    public ?string $messageEffectId = null;

    /**
     * Description of the message to reply to
     */
    #[Field('reply_parameters', required: false)]
    public ?ReplyParameters $replyParameters = null;

    /**
     * Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
     */
    #[Field('reply_markup', required: false)]
    public InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null;

    public function __construct(
        int|string $chatId,
        string $question,
        array $options,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $questionParseMode = null,
        ?array $questionEntities = null,
        ?bool $isAnonymous = null,
        ?string $type = null,
        ?bool $allowsMultipleAnswers = null,
        ?bool $allowsRevoting = null,
        ?bool $shuffleOptions = null,
        ?bool $allowAddingOptions = null,
        ?bool $hideResultsUntilCloses = null,
        ?bool $membersOnly = null,
        ?array $countryCodes = null,
        ?array $correctOptionIds = null,
        ?string $explanation = null,
        ?string $explanationParseMode = null,
        ?array $explanationEntities = null,
        ?InputPollMedia $explanationMedia = null,
        ?int $openPeriod = null,
        ?int $closeDate = null,
        ?bool $isClosed = null,
        ?string $description = null,
        ?string $descriptionParseMode = null,
        ?array $descriptionEntities = null,
        ?InputPollMedia $media = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($question !== null) $this->question = $question;
        if ($options !== null) $this->options = $options;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($questionParseMode !== null) $this->questionParseMode = $questionParseMode;
        if ($questionEntities !== null) $this->questionEntities = $questionEntities;
        if ($isAnonymous !== null) $this->isAnonymous = $isAnonymous;
        if ($type !== null) $this->type = $type;
        if ($allowsMultipleAnswers !== null) $this->allowsMultipleAnswers = $allowsMultipleAnswers;
        if ($allowsRevoting !== null) $this->allowsRevoting = $allowsRevoting;
        if ($shuffleOptions !== null) $this->shuffleOptions = $shuffleOptions;
        if ($allowAddingOptions !== null) $this->allowAddingOptions = $allowAddingOptions;
        if ($hideResultsUntilCloses !== null) $this->hideResultsUntilCloses = $hideResultsUntilCloses;
        if ($membersOnly !== null) $this->membersOnly = $membersOnly;
        if ($countryCodes !== null) $this->countryCodes = $countryCodes;
        if ($correctOptionIds !== null) $this->correctOptionIds = $correctOptionIds;
        if ($explanation !== null) $this->explanation = $explanation;
        if ($explanationParseMode !== null) $this->explanationParseMode = $explanationParseMode;
        if ($explanationEntities !== null) $this->explanationEntities = $explanationEntities;
        if ($explanationMedia !== null) $this->explanationMedia = $explanationMedia;
        if ($openPeriod !== null) $this->openPeriod = $openPeriod;
        if ($closeDate !== null) $this->closeDate = $closeDate;
        if ($isClosed !== null) $this->isClosed = $isClosed;
        if ($description !== null) $this->description = $description;
        if ($descriptionParseMode !== null) $this->descriptionParseMode = $descriptionParseMode;
        if ($descriptionEntities !== null) $this->descriptionEntities = $descriptionEntities;
        if ($media !== null) $this->media = $media;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
    }

    public static function make(
        int|string $chatId,
        string $question,
        array $options,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $questionParseMode = null,
        ?array $questionEntities = null,
        ?bool $isAnonymous = null,
        ?string $type = null,
        ?bool $allowsMultipleAnswers = null,
        ?bool $allowsRevoting = null,
        ?bool $shuffleOptions = null,
        ?bool $allowAddingOptions = null,
        ?bool $hideResultsUntilCloses = null,
        ?bool $membersOnly = null,
        ?array $countryCodes = null,
        ?array $correctOptionIds = null,
        ?string $explanation = null,
        ?string $explanationParseMode = null,
        ?array $explanationEntities = null,
        ?InputPollMedia $explanationMedia = null,
        ?int $openPeriod = null,
        ?int $closeDate = null,
        ?bool $isClosed = null,
        ?string $description = null,
        ?string $descriptionParseMode = null,
        ?array $descriptionEntities = null,
        ?InputPollMedia $media = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null
    ): static
    {
        return new static($chatId, $question, $options, $businessConnectionId, $messageThreadId, $questionParseMode, $questionEntities, $isAnonymous, $type, $allowsMultipleAnswers, $allowsRevoting, $shuffleOptions, $allowAddingOptions, $hideResultsUntilCloses, $membersOnly, $countryCodes, $correctOptionIds, $explanation, $explanationParseMode, $explanationEntities, $explanationMedia, $openPeriod, $closeDate, $isClosed, $description, $descriptionParseMode, $descriptionEntities, $media, $disableNotification, $protectContent, $allowPaidBroadcast, $messageEffectId, $replyParameters, $replyMarkup);
    }
}
