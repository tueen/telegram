<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\BotShortDescription;

/**
 * Use this method to get the current bot short description for the given user language. Returns BotShortDescription on success.
 *
 * @link https://core.telegram.org/bots/api#getmyshortdescription
 */
#[ApiMethod('getMyShortDescription', 'POST')]
#[ReturnType(BotShortDescription::class, isArray: false)]
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
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
