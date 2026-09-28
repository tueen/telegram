<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to process a received chat join request query by showing a Mini App to the user before deciding the outcome. Call answerChatJoinRequestQuery to resolve the join request query based on the user interaction with the Mini App. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#sendchatjoinrequestwebapp
 */
#[ApiMethod('sendChatJoinRequestWebApp', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SendChatJoinRequestWebApp extends Method
{
    /**
     * Unique identifier of the join request query
     */
    #[Field('chat_join_request_query_id', required: true)]
    public string $chatJoinRequestQueryId;

    /**
     * An HTTPS URL of a Web App to be opened with additional data as specified in Initializing Web Apps
     */
    #[Field('web_app_url', required: true)]
    public string $webAppUrl;

    public function __construct(
        string $chatJoinRequestQueryId,
        string $webAppUrl,
        mixed ...$extra
    )
    {
        $this->chatJoinRequestQueryId = $chatJoinRequestQueryId;
        $this->webAppUrl = $webAppUrl;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
