<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\MaskPosition;

/**
 * Use this method to change the mask position of a mask sticker. The sticker must belong to a sticker set that was created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickermaskposition
 */
#[ApiMethod('setStickerMaskPosition', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetStickerMaskPosition extends Method
{
    /**
     * File identifier of the sticker
     */
    #[Field('sticker', required: true)]
    public string $sticker;

    /**
     * A JSON-serialized object with the position where the mask should be placed on faces. Omit the parameter to remove the mask position.
     */
    #[Field('mask_position', required: false)]
    public ?MaskPosition $maskPosition = null;

    public function __construct(
        string $sticker,
        ?MaskPosition $maskPosition = null,
        mixed ...$extra
    )
    {
        if ($sticker !== null) $this->sticker = $sticker;
        if ($maskPosition !== null) $this->maskPosition = $maskPosition;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
