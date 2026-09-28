<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\CommandInvalidException;
use Tueen\Telegram\Exceptions\CommandTooLongException;
use Tueen\Telegram\Exceptions\CommandsListEmptyException;
use Tueen\Telegram\Exceptions\InvalidLanguageCodeException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\TooManyCommandsException;
use Tueen\Telegram\Types\BotCommandScope;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change the list of the bot's commands. See this manual for more details about bot commands. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmycommands
 *
 * @throws CommandsListEmptyException
 * @throws TooManyCommandsException
 * @throws CommandTooLongException
 * @throws CommandInvalidException
 * @throws InvalidLanguageCodeException
 * @throws ChatNotFoundException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setMyCommands', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::CommandsListEmpty, TelegramErrorCode::TooManyCommands, TelegramErrorCode::CommandTooLong, TelegramErrorCode::CommandInvalid, TelegramErrorCode::InvalidLanguageCode, TelegramErrorCode::ChatNotFound, TelegramErrorCode::FloodWait])]
class SetMyCommands extends Method
{
    /**
     * A JSON-serialized list of bot commands to be set as the list of the bot's commands. At most 100 commands can be specified.
     */
    #[Field('commands', required: true)]
    public array $commands;

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
        array $commands,
        ?BotCommandScope $scope = null,
        ?string $languageCode = null,
        mixed ...$extra
    )
    {
        $this->commands = $commands;
        if ($scope !== null) $this->scope = $scope;
        if ($languageCode !== null) $this->languageCode = $languageCode;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
