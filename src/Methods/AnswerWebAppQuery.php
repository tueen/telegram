<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\SentWebAppMessage;
use Tueen\Telegram\Types\InlineQueryResult;

/**
 * Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 *
 * @link https://core.telegram.org/bots/api#answerwebappquery
 */
#[ApiMethod('answerWebAppQuery', 'POST')]
#[ReturnType(SentWebAppMessage::class, isArray: false)]
class AnswerWebAppQuery extends Method
{
    /**
     * Unique identifier for the query to be answered
     */
    #[Field('web_app_query_id', required: true)]
    public string $webAppQueryId;

    /**
     * A JSON-serialized object describing the message to be sent
     */
    #[Field('result', required: true)]
    public InlineQueryResult $result;

    public function __construct(
        string $webAppQueryId,
        InlineQueryResult $result,
        mixed ...$extra
    )
    {
        if ($webAppQueryId !== null) $this->webAppQueryId = $webAppQueryId;
        if ($result !== null) $this->result = $result;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
