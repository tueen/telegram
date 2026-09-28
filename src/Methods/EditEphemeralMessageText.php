<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InputRichMessage;
use Tueen\Telegram\Types\LinkPreviewOptions;

/**
 * Use this method to edit an ephemeral text or rich message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#editephemeralmessagetext
 */
#[ApiMethod('editEphemeralMessageText', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class EditEphemeralMessageText extends Method
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
     * New text of the message, 1-4096 characters after entity parsing; required if rich_message isn't specified
     */
    #[Field('text', required: false)]
    public ?string $text = null;

    /**
     * Mode for parsing entities in the message text. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ParseMode|string|null $parseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
     */
    #[Field('entities', required: false)]
    public ?array $entities = null;

    /**
     * New rich content of the message; required if text isn't specified
     */
    #[Field('rich_message', required: false)]
    public ?InputRichMessage $richMessage = null;

    /**
     * Link preview generation options for the message
     */
    #[Field('link_preview_options', required: false)]
    public ?LinkPreviewOptions $linkPreviewOptions = null;

    /**
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        ?string $text = null,
        ParseMode|string|null $parseMode = null,
        ?array $entities = null,
        ?InputRichMessage $richMessage = null,
        ?LinkPreviewOptions $linkPreviewOptions = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($receiverUserId !== null) $this->receiverUserId = $receiverUserId;
        if ($ephemeralMessageId !== null) $this->ephemeralMessageId = $ephemeralMessageId;
        if ($text !== null) $this->text = $text;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($entities !== null) $this->entities = $entities;
        if ($richMessage !== null) $this->richMessage = $richMessage;
        if ($linkPreviewOptions !== null) $this->linkPreviewOptions = $linkPreviewOptions;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
