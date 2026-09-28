<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete a sticker from a set created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletestickerfromset
 */
#[ApiMethod('deleteStickerFromSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteStickerFromSet extends Method
{
    /**
     * File identifier of the sticker
     */
    #[Field('sticker', required: true)]
    public string $sticker;

    public function __construct(
        string $sticker,
        mixed ...$extra
    )
    {
        if ($sticker !== null) $this->sticker = $sticker;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
