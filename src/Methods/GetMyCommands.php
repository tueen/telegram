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
use Tueen\Telegram\Types\BotCommand;
use Tueen\Telegram\Types\BotCommandScope;

/**
 * Use this method to get the current list of the bot's commands for the given scope and user language. Returns an Array of BotCommand objects. If commands aren't set, an empty list is returned.
 *
 * @link https://core.telegram.org/bots/api#getmycommands
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('getMyCommands', 'POST')]
#[ReturnType(BotCommand::class, isArray: true)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class GetMyCommands extends Method
{
    /**
     * A JSON-serialized object, describing scope of users. Defaults to BotCommandScopeDefault.
     */
    #[Field('scope', required: false)]
    public ?BotCommandScope $scope = null;

    /**
     * A two-letter ISO 639-1 language code or an empty string
     */
    #[Field('language_code', required: false)]
    public ?string $languageCode = null;

    public function __construct(
        ?BotCommandScope $scope = null,
        ?string $languageCode = null,
        mixed ...$extra
    )
    {
        if ($scope !== null) $this->scope = $scope;
        if ($languageCode !== null) $this->languageCode = $languageCode;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
