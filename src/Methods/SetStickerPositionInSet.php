<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickerpositioninset
 */
#[ApiMethod('setStickerPositionInSet', 'POST')]
#[ReturnType(Type::class, isArray: false)]
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

    public static function make(
        string $sticker,
        int $position
    ): static
    {
        return new static($sticker, $position);
    }
}
