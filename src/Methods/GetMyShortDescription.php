<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Types\BotShortDescription;

/**
 * Use this method to get the current bot short description for the given user language. Returns BotShortDescription on success.
 *
 * @link https://core.telegram.org/bots/api#getmyshortdescription
 *
 * @throws UnauthorizedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getMyShortDescription', 'POST')]
#[ReturnType(BotShortDescription::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::Unauthorized, TelegramErrorCode::FloodWait])]
class GetMyShortDescription extends Method
{
    /**
     * A two-letter ISO 639-1 language code or an empty string
     */
    #[Field('language_code', required: false)]
    public ?string $languageCode = null;

    public function __construct(
        ?string $languageCode = null,
        mixed ...$extra
    )
    {
        if ($languageCode !== null) $this->languageCode = $languageCode;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
