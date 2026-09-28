<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InlineQueryResultsButton;

/**
 * Use this method to send answers to an inline query. On success, True is returned.
 * No more than 50 results per query are allowed.
 *
 * @link https://core.telegram.org/bots/api#answerinlinequery
 */
#[ApiMethod('answerInlineQuery', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class AnswerInlineQuery extends Method
{
    /**
     * Unique identifier for the answered query
     */
    #[Field('inline_query_id', required: true)]
    public ?string $inlineQueryId = null;

    /**
     * A JSON-serialized Array of results for the inline query
     */
    #[Field('results', required: true)]
    public ?array $results = null;

    /**
     * The maximum amount of time in seconds that the result of the inline query may be cached on the server. Defaults to 300.
     */
    #[Field('cache_time', required: false)]
    public ?int $cacheTime = null;

    /**
     * Pass True if results may be cached on the server side only for the user that sent the query. By default, results may be returned to any user who sends the same query.
     */
    #[Field('is_personal', required: false)]
    public ?bool $isPersonal = null;

    /**
     * Pass the offset that a client should send in the next query with the same text to receive more results. Pass an empty string if there are no more results or if you don't support pagination. Offset length can't exceed 64 bytes.
     */
    #[Field('next_offset', required: false)]
    public ?string $nextOffset = null;

    /**
     * A JSON-serialized object describing a button to be shown above inline query results
     */
    #[Field('button', required: false)]
    public ?InlineQueryResultsButton $button = null;

    public function __construct(
        ?string $inlineQueryId = null,
        ?array $results = null,
        ?int $cacheTime = null,
        ?bool $isPersonal = null,
        ?string $nextOffset = null,
        ?InlineQueryResultsButton $button = null,
        mixed ...$extra
    )
    {
        if ($inlineQueryId !== null) $this->inlineQueryId = $inlineQueryId;
        if ($results !== null) $this->results = $results;
        if ($cacheTime !== null) $this->cacheTime = $cacheTime;
        if ($isPersonal !== null) $this->isPersonal = $isPersonal;
        if ($nextOffset !== null) $this->nextOffset = $nextOffset;
        if ($button !== null) $this->button = $button;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
