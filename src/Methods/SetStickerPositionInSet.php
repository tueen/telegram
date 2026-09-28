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
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickerpositioninset
 *
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('setStickerPositionInSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::FloodWait])]
class SetStickerPositionInSet extends Method
{
    /**
     * File identifier of the sticker
     */
    #[Field('sticker', required: true)]
    public string $sticker;

    /**
     * New sticker position in the set, zero-based
     */
    #[Field('position', required: true)]
    public int $position;

    public function __construct(
        string $sticker,
        int $position,
        mixed ...$extra
    )
    {
        $this->sticker = $sticker;
        $this->position = $position;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
