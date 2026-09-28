<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\PollType;

/**
 * This object contains information about a poll.
 *
 * @link https://core.telegram.org/bots/api#poll
 */
class Poll extends Type
{
    /**
     * Unique poll identifier
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Poll question, 1-300 characters
     */
    #[Field('question', required: true)]
    private(set) ?string $question = null;

    /**
     * Optional. Special entities that appear in the question. Currently, only custom emoji entities are allowed in poll questions
     * @var MessageEntity[]|null
     */
    #[Field('question_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $questionEntities = null;

    /**
     * List of poll options
     * @var PollOption[]|null
     */
    #[Field('options', required: true)]
    #[ArrayOf(PollOption::class)]
    private(set) ?array $options = null;

    /**
     * Total number of users that voted in the poll
     */
    #[Field('total_voter_count', required: true)]
    private(set) ?int $totalVoterCount = null;

    /**
     * True, if the poll is closed
     */
    #[Field('is_closed', required: true)]
    private(set) ?bool $isClosed = null;

    /**
     * True, if the poll is anonymous
     */
    #[Field('is_anonymous', required: true)]
    private(set) ?bool $isAnonymous = null;

    /**
     * Poll type, currently can be "regular" or "quiz"
     */
    #[Field('type', required: true)]
    private(set) PollType|string|null $type = null;

    /**
     * True, if the poll allows multiple answers
     */
    #[Field('allows_multiple_answers', required: true)]
    private(set) ?bool $allowsMultipleAnswers = null;

    /**
     * True, if the poll allows to change the chosen answer options
     */
    #[Field('allows_revoting', required: true)]
    private(set) ?bool $allowsRevoting = null;

    /**
     * True if voting is limited to users who have been members of the chat where the poll was originally sent for more than 24 hours
     */
    #[Field('members_only', required: true)]
    private(set) ?bool $membersOnly = null;

    /**
     * Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which users can vote in the poll. The country code "FT" is used for users with anonymous numbers. If omitted, then users from any country can participate in the poll.
     * @var String[]|null
     */
    #[Field('country_codes', required: false)]
    private(set) ?array $countryCodes = null;

    /**
     * Optional. Array of 0-based identifiers of the correct answer options. Available only for polls in quiz mode which are closed or were sent (not forwarded) by the bot or to the private chat with the bot.
     * @var Integer[]|null
     */
    #[Field('correct_option_ids', required: false)]
    private(set) ?array $correctOptionIds = null;

    /**
     * Optional. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
     */
    #[Field('explanation', required: false)]
    private(set) ?string $explanation = null;

    /**
     * Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
     * @var MessageEntity[]|null
     */
    #[Field('explanation_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $explanationEntities = null;

    /**
     * Optional. Media added to the quiz explanation
     */
    #[Field('explanation_media', required: false)]
    private(set) ?PollMedia $explanationMedia = null;

    /**
     * Optional. Amount of time in seconds the poll will be active after creation
     */
    #[Field('open_period', required: false)]
    private(set) ?int $openPeriod = null;

    /**
     * Optional. Point in time (Unix timestamp) when the poll will be automatically closed
     */
    #[Field('close_date', required: false)]
    private(set) ?int $closeDate = null;

    /**
     * Optional. Description of the poll; for polls inside the Message object only
     */
    #[Field('description', required: false)]
    private(set) ?string $description = null;

    /**
     * Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the description
     * @var MessageEntity[]|null
     */
    #[Field('description_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $descriptionEntities = null;

    /**
     * Optional. Media added to the poll description; for polls inside the Message object only
     */
    #[Field('media', required: false)]
    private(set) ?PollMedia $media = null;

}
