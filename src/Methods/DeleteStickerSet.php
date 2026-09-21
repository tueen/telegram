<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete a sticker set that was created by the bot. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletestickerset
 */
#[ApiMethod('deleteStickerSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteStickerSet extends Method
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    public function __construct(
        string $name
    )
    {
        if ($name !== null) $this->name = $name;
    }

    public static function make(
        string $name
    ): static
    {
        return new static($name);
    }
}
