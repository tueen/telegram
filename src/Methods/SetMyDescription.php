<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the bot's description, which is shown in the chat with the bot if the chat is empty. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmydescription
 */
#[ApiMethod('setMyDescription', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetMyDescription extends Method
{
    /**
     * New bot description; 0-512 characters. Pass an empty string to remove the dedicated description for the given language.
     */
    #[Field('description', required: false)]
    public ?string $description = null;

    /**
     * A two-letter ISO 639-1 language code. If empty, the description will be applied to all users for whose language there is no dedicated description.
     */
    #[Field('language_code', required: false)]
    public ?string $languageCode = null;

    public function __construct(
        ?string $description = null,
        ?string $languageCode = null,
        mixed ...$extra
    )
    {
        if ($description !== null) $this->description = $description;
        if ($languageCode !== null) $this->languageCode = $languageCode;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
