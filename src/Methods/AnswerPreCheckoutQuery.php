<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\QueryIdInvalidException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 *
 * @link https://core.telegram.org/bots/api#answerprecheckoutquery
 *
 * @throws QueryIdInvalidException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('answerPreCheckoutQuery', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::QueryIdInvalid, TelegramErrorCode::FloodWait])]
class AnswerPreCheckoutQuery extends Method
{
    /**
     * Unique identifier for the query to be answered
     */
    #[Field('pre_checkout_query_id', required: true)]
    public ?string $preCheckoutQueryId = null;

    /**
     * Specify True if everything is alright (goods are available, etc.) and the bot is ready to proceed with the order. Use False if there are any problems.
     */
    #[Field('ok', required: true)]
    public ?bool $ok = null;

    /**
     * Required if ok is False. Error message in human readable form that explains the reason for failure to proceed with the checkout (e.g. "Sorry, somebody just bought the last of our amazing black T-shirts while you were busy filling out your payment details. Please choose a different color or garment!"). Telegram will display this message to the user.
     */
    #[Field('error_message', required: false)]
    public ?string $errorMessage = null;

    public function __construct(
        ?string $preCheckoutQueryId = null,
        ?bool $ok = null,
        ?string $errorMessage = null,
        mixed ...$extra
    )
    {
        if ($preCheckoutQueryId !== null) $this->preCheckoutQueryId = $preCheckoutQueryId;
        if ($ok !== null) $this->ok = $ok;
        if ($errorMessage !== null) $this->errorMessage = $errorMessage;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
