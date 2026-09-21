<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to process a received chat join request query. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#answerchatjoinrequestquery
 */
#[ApiMethod('answerChatJoinRequestQuery', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class AnswerChatJoinRequestQuery extends Method
{
    /**
     * Unique identifier of the join request query
     */
    #[Field('chat_join_request_query_id', required: true)]
    public string $chatJoinRequestQueryId;

    /**
     * Result of the query. Must be either "approve" to allow the user to join the chat, "decline" to disallow the user to join the chat, or "queue" to leave the decision to other administrators.
     */
    #[Field('result', required: true)]
    public string $result;

    public function __construct(
        string $chatJoinRequestQueryId,
        string $result
    )
    {
        if ($chatJoinRequestQueryId !== null) $this->chatJoinRequestQueryId = $chatJoinRequestQueryId;
        if ($result !== null) $this->result = $result;
    }

    public static function make(
        string $chatJoinRequestQueryId,
        string $result
    ): static
    {
        return new static($chatJoinRequestQueryId, $result);
    }
}
