<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickerpositioninset
 */
#[ApiMethod('setStickerPositionInSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
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
        int $position
    )
    {
        if ($sticker !== null) $this->sticker = $sticker;
        if ($position !== null) $this->position = $position;
    }
}
