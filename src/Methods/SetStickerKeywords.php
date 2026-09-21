<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to change search keywords assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickerkeywords
 */
#[ApiMethod('setStickerKeywords', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetStickerKeywords extends Method
{
    /**
     * File identifier of the sticker
     */
    #[Field('sticker', required: true)]
    public string $sticker;

    /**
     * A JSON-serialized list of 0-20 search keywords for the sticker with total length of up to 64 characters
     */
    #[Field('keywords', required: false)]
    public ?array $keywords = null;

    public function __construct(
        string $sticker,
        ?array $keywords = null
    )
    {
        if ($sticker !== null) $this->sticker = $sticker;
        if ($keywords !== null) $this->keywords = $keywords;
    }

    public static function make(
        string $sticker,
        ?array $keywords = null
    ): static
    {
        return new static($sticker, $keywords);
    }
}
