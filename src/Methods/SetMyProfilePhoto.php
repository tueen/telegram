<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputProfilePhoto;

/**
 * Changes the profile photo of the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmyprofilephoto
 */
#[ApiMethod('setMyProfilePhoto', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetMyProfilePhoto extends Method
{
    /**
     * The new profile photo to set
     */
    #[Field('photo', required: true)]
    public InputProfilePhoto $photo;

    public function __construct(
        InputProfilePhoto $photo,
        mixed ...$extra
    )
    {
        $this->photo = $photo;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
