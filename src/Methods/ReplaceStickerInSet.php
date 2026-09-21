<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputSticker;

/**
 * Use this method to replace an existing sticker in a sticker set with a new one. The method is equivalent to calling deleteStickerFromSet, then addStickerToSet, then setStickerPositionInSet. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#replacestickerinset
 */
#[ApiMethod('replaceStickerInSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class ReplaceStickerInSet extends Method
{
    /**
     * User identifier of the sticker set owner
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * File identifier of the replaced sticker
     */
    #[Field('old_sticker', required: true)]
    public string $oldSticker;

    /**
     * A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set remains unchanged.
     */
    #[Field('sticker', required: true)]
    public InputSticker $sticker;

    public function __construct(
        int $userId,
        string $name,
        string $oldSticker,
        InputSticker $sticker
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($name !== null) $this->name = $name;
        if ($oldSticker !== null) $this->oldSticker = $oldSticker;
        if ($sticker !== null) $this->sticker = $sticker;
    }
}
