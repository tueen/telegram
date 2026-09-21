<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Enums\StickerType;

/**
 * Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#createnewstickerset
 */
#[ApiMethod('createNewStickerSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class CreateNewStickerSet extends Method
{
    /**
     * User identifier of created sticker set owner
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * Short name of sticker set, to be used in t.me/addstickers/ URLs (e.g., animals). Can contain only English letters, digits and underscores. Must begin with a letter, can't contain consecutive underscores and must end in "_by_<bot_username>". <bot_username> is case insensitive. 1-64 characters.
     */
    #[Field('name', required: true)]
    public string $name;

    /**
     * Sticker set title, 1-64 characters
     */
    #[Field('title', required: true)]
    public string $title;

    /**
     * A JSON-serialized list of 1-50 initial stickers to be added to the sticker set
     */
    #[Field('stickers', required: true)]
    public array $stickers;

    /**
     * Type of stickers in the set, pass "regular", "mask", or "custom_emoji". By default, a regular sticker set is created.
     */
    #[Field('sticker_type', required: false)]
    public StickerType|string|null $stickerType = null;

    /**
     * Pass True if stickers in the sticker set must be repainted to the color of text when used in messages, the accent color if used as emoji status, white on chat photos, or another appropriate color based on context; for custom emoji sticker sets only
     */
    #[Field('needs_repainting', required: false)]
    public ?bool $needsRepainting = null;

    public function __construct(
        int $userId,
        string $name,
        string $title,
        array $stickers,
        StickerType|string|null $stickerType = null,
        ?bool $needsRepainting = null
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($name !== null) $this->name = $name;
        if ($title !== null) $this->title = $title;
        if ($stickers !== null) $this->stickers = $stickers;
        if ($stickerType !== null) $this->stickerType = $stickerType;
        if ($needsRepainting !== null) $this->needsRepainting = $needsRepainting;
    }
}
