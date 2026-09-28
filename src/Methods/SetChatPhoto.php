<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\InputFile;

/**
 * Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#setchatphoto
 */
#[ApiMethod('setChatPhoto', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetChatPhoto extends Method
{
    /**
     * Unique identifier for the target chat or username of the target channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * New chat photo, uploaded using multipart/form-data
     */
    #[Field('photo', required: true)]
    #[RequiresUpload]
    public ?InputFile $photo = null;

    public function __construct(
        int|string|null $chatId = null,
        ?InputFile $photo = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($photo !== null) $this->photo = $photo;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
