<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\BotDescription;

/**
 * Use this method to get the current bot description for the given user language. Returns BotDescription on success.
 *
 * @link https://core.telegram.org/bots/api#getmydescription
 */
#[ApiMethod('getMyDescription', 'POST')]
#[ReturnType(BotDescription::class, isArray: false)]
class GetMyDescription extends Method
{
    /**
     * A two-letter ISO 639-1 language code or an empty string
     */
    #[Field('language_code', required: false)]
    public ?string $languageCode = null;

    public function __construct(
        ?string $languageCode = null
    )
    {
        if ($languageCode !== null) $this->languageCode = $languageCode;
    }
}
