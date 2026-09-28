<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\PhotoInvalidDimensionsException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputProfilePhoto;

/**
 * Changes the profile photo of the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setmyprofilephoto
 *
 * @throws PhotoInvalidDimensionsException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setMyProfilePhoto', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::PhotoInvalidDimensions, TelegramErrorCode::FloodWait])]
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
