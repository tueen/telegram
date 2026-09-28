<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\QueryIdInvalidException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\InlineQueryResult;
use Tueen\Telegram\Types\SentWebAppMessage;

/**
 * Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 *
 * @link https://core.telegram.org/bots/api#answerwebappquery
 *
 * @throws QueryIdInvalidException
 * @throws BotKickedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('answerWebAppQuery', 'POST')]
#[ReturnType(SentWebAppMessage::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::QueryIdInvalid, TelegramErrorCode::BotKicked, TelegramErrorCode::FloodWait])]
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
        $this->webAppQueryId = $webAppQueryId;
        $this->result = $result;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
