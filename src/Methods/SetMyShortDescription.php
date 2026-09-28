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
 * Use this method to change the bot's short description, which is shown on the bot's profile page and is sent together with the link when users share the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmyshortdescription
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setMyShortDescription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class SetMyShortDescription extends Method
{
    /**
     * New short description for the bot; 0-120 characters. Pass an empty string to remove the dedicated short description for the given language.
     */
    #[Field('short_description', required: false)]
    public ?string $shortDescription = null;

    /**
     * A two-letter ISO 639-1 language code. If empty, the short description will be applied to all users for whose language there is no dedicated short description.
     */
    #[Field('language_code', required: false)]
    public ?string $languageCode = null;

    public function __construct(
        ?string $shortDescription = null,
        ?string $languageCode = null,
        mixed ...$extra
    )
    {
        if ($shortDescription !== null) $this->shortDescription = $shortDescription;
        if ($languageCode !== null) $this->languageCode = $languageCode;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
