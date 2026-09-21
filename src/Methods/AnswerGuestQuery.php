<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\SentGuestMessage;
use Tueen\Telegram\Types\InlineQueryResult;

/**
 * Use this method to reply to a received guest message. On success, a SentGuestMessage object is returned.
 *
 * @link https://core.telegram.org/bots/api#answerguestquery
 */
#[ApiMethod('answerGuestQuery', 'POST')]
#[ReturnType(SentGuestMessage::class, isArray: false)]
class AnswerGuestQuery extends Method
{
    /**
     * Unique identifier for the query to be answered
     */
    #[Field('guest_query_id', required: true)]
    public string $guestQueryId;

    /**
     * A JSON-serialized object describing the message to be sent
     */
    #[Field('result', required: true)]
    public InlineQueryResult $result;

    public function __construct(
        string $guestQueryId,
        InlineQueryResult $result,
        mixed ...$extra
    )
    {
        if ($guestQueryId !== null) $this->guestQueryId = $guestQueryId;
        if ($result !== null) $this->result = $result;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
