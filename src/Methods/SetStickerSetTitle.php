<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to set the title of a created sticker set. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setstickersettitle
 */
#[ApiMethod('setStickerSetTitle', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class SetStickerSetTitle extends Method
{
    /**
     * Sticker set name
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * Sticker set title, 1-64 characters
     */
    #[Field('title', required: true)]
    public string $title;

    public function __construct(
        string $name,
        string $title
    )
    {
        if ($name !== null) $this->name = $name;
        if ($title !== null) $this->title = $title;
    }

    public static function make(
        string $name,
        string $title
    ): static
    {
        return new static($name, $title);
    }
}
