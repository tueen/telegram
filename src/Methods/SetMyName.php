<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the bot's name. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmyname
 */
#[ApiMethod('setMyName', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
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
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
