<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#deletechatstickerset
 */
#[ApiMethod('deleteChatStickerSet', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class DeleteChatStickerSet extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    public function __construct(
        int|string $chatId,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
