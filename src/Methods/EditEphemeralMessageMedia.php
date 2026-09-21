<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InputMedia;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to edit the media of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#editephemeralmessagemedia
 */
#[ApiMethod('editEphemeralMessageMedia', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class EditEphemeralMessageMedia extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Identifier of the user who received the message
     */
    #[Field('receiver_user_id', required: true)]
    public int $receiverUserId;

    /**
     * Identifier of the ephemeral message to edit
     */
    #[Field('ephemeral_message_id', required: true)]
    public int $ephemeralMessageId;

    /**
     * A JSON-serialized object for the new media content of the message
     */
    #[Field('media', required: true)]
    public InputMedia $media;

    /**
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        InputMedia $media,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($receiverUserId !== null) $this->receiverUserId = $receiverUserId;
        if ($ephemeralMessageId !== null) $this->ephemeralMessageId = $ephemeralMessageId;
        if ($media !== null) $this->media = $media;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
