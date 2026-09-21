<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\User;

/**
 * A simple method for testing your bot's authentication token. Requires no parameters. Returns basic information about the bot in form of a User object.
 *
 * @link https://core.telegram.org/bots/api#getme
 */
#[ApiMethod('getMe', 'POST')]
#[ReturnType(User::class, isArray: false)]
class GetMe extends Method
{


    public function __construct()
    {

    }
}
