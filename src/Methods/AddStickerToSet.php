<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputSticker;

/**
 * Use this method to add a new sticker to a set created by the bot. Emoji sticker sets can have up to 200 stickers. Other sticker sets can have up to 120 stickers. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#addstickertoset
 */
#[ApiMethod('addStickerToSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class AddStickerToSet extends Method
{
    /**
     * User identifier of sticker set owner
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set isn't changed.
     */
    #[Field('sticker', required: true)]
    public InputSticker $sticker;

    public function __construct(
        int $userId,
        string $name,
        InputSticker $sticker,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($name !== null) $this->name = $name;
        if ($sticker !== null) $this->sticker = $sticker;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
