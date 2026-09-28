<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\MessageTooLongException;
use Tueen\Telegram\Exceptions\QueryIdInvalidException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#answercallbackquery
 *
 * @throws QueryIdInvalidException
 * @throws MessageTooLongException
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('answerCallbackQuery', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::QueryIdInvalid, TelegramErrorCode::MessageTooLong, TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class AnswerCallbackQuery extends Method
{
    /**
     * Unique identifier for the query to be answered
     */
    #[Field('callback_query_id', required: true)]
    public ?string $callbackQueryId = null;

    /**
     * Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters.
     */
    #[Field('text', required: false)]
    public ?string $text = null;

    /**
     * If True, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to False.
     */
    #[Field('show_alert', required: false)]
    public ?bool $showAlert = null;

    /**
     * URL that will be opened by the user's client. If you have created a Game and accepted the conditions via @BotFather, specify the URL that opens your game - note that this will only work if the query comes from a callback_game button. Otherwise, you may use links like t.me/your_bot?start=XXXX that open your bot with a parameter.
     */
    #[Field('url', required: false)]
    public ?string $url = null;

    /**
     * The maximum amount of time in seconds that the result of the callback query may be cached client-side. Defaults to 0.
     */
    #[Field('cache_time', required: false)]
    public ?int $cacheTime = null;

    public function __construct(
        ?string $callbackQueryId = null,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null,
        mixed ...$extra
    )
    {
        if ($callbackQueryId !== null) $this->callbackQueryId = $callbackQueryId;
        if ($text !== null) $this->text = $text;
        if ($showAlert !== null) $this->showAlert = $showAlert;
        if ($url !== null) $this->url = $url;
        if ($cacheTime !== null) $this->cacheTime = $cacheTime;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
