<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\BotName;

/**
 * Use this method to get the current bot name for the given user language. Returns BotName on success.
 *
 * @link https://core.telegram.org/bots/api#getmyname
 */
#[ApiMethod('getMyName', 'POST')]
#[ReturnType(BotName::class, isArray: false)]
class GetMyName extends Method
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

    public static function make(
        ?string $languageCode = null
    ): static
    {
        return new static($languageCode);
    }
}
