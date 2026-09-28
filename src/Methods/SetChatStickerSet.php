<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatstickerset
 */
#[ApiMethod('setChatStickerSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetChatStickerSet extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Name of the sticker set to be set as the group sticker set
     */
    #[Field('sticker_set_name', required: true)]
    public ?string $stickerSetName = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $stickerSetName = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($stickerSetName !== null) $this->stickerSetName = $stickerSetName;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
