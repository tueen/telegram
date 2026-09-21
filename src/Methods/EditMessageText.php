<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Types\LinkPreviewOptions;
use Tueen\Telegram\Types\InputRichMessage;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to edit text, rich and game messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within 48 hours from the time they were sent.
 *
 * @link https://core.telegram.org/bots/api#editmessagetext
 */
#[ApiMethod('editMessageText', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class EditMessageText extends Method
{
    /**
     * Unique identifier of the business connection on behalf of which the message to be edited was sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username.
     */
    #[Field('chat_id', required: false)]
    public int|string|null $chatId = null;

    /**
     * Required if inline_message_id is not specified. Identifier of the message to edit.
     */
    #[Field('message_id', required: false)]
    public ?int $messageId = null;

    /**
     * Required if chat_id and message_id are not specified. Identifier of the inline message.
     */
    #[Field('inline_message_id', required: false)]
    public ?string $inlineMessageId = null;

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
     * Link preview generation options for the message
     */
    #[Field('link_preview_options', required: false)]
    public ?LinkPreviewOptions $linkPreviewOptions = null;

    /**
     * New rich content of the message; required if text isn't specified. Direct upload of new files and explicit upload of files by a URL isn't supported when an inline message is edited.
     */
    #[Field('rich_message', required: false)]
    public ?InputRichMessage $richMessage = null;

    /**
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $text = null,
        ParseMode|string|null $parseMode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $linkPreviewOptions = null,
        ?InputRichMessage $richMessage = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($inlineMessageId !== null) $this->inlineMessageId = $inlineMessageId;
        if ($text !== null) $this->text = $text;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($entities !== null) $this->entities = $entities;
        if ($linkPreviewOptions !== null) $this->linkPreviewOptions = $linkPreviewOptions;
        if ($richMessage !== null) $this->richMessage = $richMessage;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
