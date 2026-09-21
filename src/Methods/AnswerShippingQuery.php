<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#answershippingquery
 */
#[ApiMethod('answerShippingQuery', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class AnswerShippingQuery extends Method
{
    /**
     * Unique identifier for the query to be answered
     */
    #[Field('shipping_query_id', required: true)]
    public string $shippingQueryId;

    /**
     * Pass True if delivery to the specified address is possible and False if there are any problems (for example, if delivery to the specified address is not possible)
     */
    #[Field('ok', required: true)]
    public bool $ok;

    /**
     * Required if ok is True. A JSON-serialized Array of available shipping options.
     */
    #[Field('shipping_options', required: false)]
    public ?array $shippingOptions = null;

    /**
     * Required if ok is False. Error message in human readable form that explains why it is impossible to complete the order (e.g. "Sorry, delivery to your desired address is unavailable"). Telegram will display this message to the user.
     */
    #[Field('error_message', required: false)]
    public ?string $errorMessage = null;

    public function __construct(
        string $shippingQueryId,
        bool $ok,
        ?array $shippingOptions = null,
        ?string $errorMessage = null
    )
    {
        if ($shippingQueryId !== null) $this->shippingQueryId = $shippingQueryId;
        if ($ok !== null) $this->ok = $ok;
        if ($shippingOptions !== null) $this->shippingOptions = $shippingOptions;
        if ($errorMessage !== null) $this->errorMessage = $errorMessage;
    }

    public static function make(
        string $shippingQueryId,
        bool $ok,
        ?array $shippingOptions = null,
        ?string $errorMessage = null
    ): static
    {
        return new static($shippingQueryId, $ok, $shippingOptions, $errorMessage);
    }
}
