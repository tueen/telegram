<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\StickerType;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\StickerDimensionsInvalidException;
use Tueen\Telegram\Exceptions\StickerEmojiInvalidException;
use Tueen\Telegram\Exceptions\StickerSetInvalidException;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#createnewstickerset
 *
 * @throws StickerSetInvalidException
 * @throws StickerDimensionsInvalidException
 * @throws StickerEmojiInvalidException
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('createNewStickerSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::StickerSetInvalid, TelegramErrorCode::StickerDimensionsInvalid, TelegramErrorCode::StickerEmojiInvalid, TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class CreateNewStickerSet extends Method
{
    /**
     * User identifier of created sticker set owner
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * Short name of sticker set, to be used in t.me/addstickers/ URLs (e.g., animals). Can contain only English letters, digits and underscores. Must begin with a letter, can't contain consecutive underscores and must end in "_by_<bot_username>". <bot_username> is case insensitive. 1-64 characters.
     */
    #[Field('name', required: true)]
    public ?string $name = null;

    /**
     * Sticker set title, 1-64 characters
     */
    #[Field('title', required: true)]
    public ?string $title = null;

    /**
     * A JSON-serialized list of 1-50 initial stickers to be added to the sticker set
     */
    #[Field('stickers', required: true)]
    public ?array $stickers = null;

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
        ?int $userId = null,
        ?string $name = null,
        ?string $title = null,
        ?array $stickers = null,
        StickerType|string|null $stickerType = null,
        ?bool $needsRepainting = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($name !== null) $this->name = $name;
        if ($title !== null) $this->title = $title;
        if ($stickers !== null) $this->stickers = $stickers;
        if ($stickerType !== null) $this->stickerType = $stickerType;
        if ($needsRepainting !== null) $this->needsRepainting = $needsRepainting;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
