<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Attributes\RequiresUpload;

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
    public int|string $chatId;

    /**
     * New chat photo, uploaded using multipart/form-data
     */
    #[Field('photo', required: true)]
    #[RequiresUpload]
    public InputFile $photo;

    public function __construct(
        int|string $chatId,
        InputFile $photo
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($photo !== null) $this->photo = $photo;
    }
}
