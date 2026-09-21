<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Removes the profile photo of the bot. Requires no parameters. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#removemyprofilephoto
 */
#[ApiMethod('removeMyProfilePhoto', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class RemoveMyProfilePhoto extends Method
{


    public function __construct()
    {

    }

    public static function make(): static
    {
        return new static();
    }
}
