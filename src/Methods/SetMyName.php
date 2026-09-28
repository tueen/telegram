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
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the bot's name. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmyname
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setMyName', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class SetMyName extends Method
{
    /**
     * New bot name; 0-64 characters. Pass an empty string to remove the dedicated name for the given language.
     */
    #[Field('name', required: false)]
    public ?string $name = null;

    /**
     * A two-letter ISO 639-1 language code. If empty, the name will be shown to all users for whose language there is no dedicated name.
     */
    #[Field('language_code', required: false)]
    public ?string $languageCode = null;

    public function __construct(
        ?string $name = null,
        ?string $languageCode = null,
        mixed ...$extra
    )
    {
        if ($name !== null) $this->name = $name;
        if ($languageCode !== null) $this->languageCode = $languageCode;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
