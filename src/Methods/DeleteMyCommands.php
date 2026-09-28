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
use Tueen\Telegram\Types\BotCommandScope;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletemycommands
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('deleteMyCommands', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class DeleteMyCommands extends Method
{
    /**
     * A JSON-serialized object, describing scope of users for which the commands are relevant. Defaults to BotCommandScopeDefault.
     */
    #[Field('scope', required: false)]
    public ?BotCommandScope $scope = null;

    /**
     * A two-letter ISO 639-1 language code. If empty, commands will be applied to all users from the given scope, for whose language there are no dedicated commands.
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
