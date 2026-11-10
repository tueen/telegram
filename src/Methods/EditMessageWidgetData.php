<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Use this method to edit data of a widget in a message sent by the bot or via the bot (for inline bots). Public data of a shared widget can be changed at most once per second. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#editmessagewidgetdata
 */
#[ApiMethod('editMessageWidgetData', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class EditMessageWidgetData extends Method
{
    /**
     * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target supergroup in the format @username.
     */
    #[Field('chat_id', required: false)]
    public int|string|null $chatId = null;

    /**
     * Required if inline_message_id is not specified. Identifier of the message with the widget.
     */
    #[Field('message_id', required: false)]
    public ?int $messageId = null;

    /**
     * Required if chat_id and message_id are not specified. Identifier of the inline message.
     */
    #[Field('inline_message_id', required: false)]
    public ?string $inlineMessageId = null;

    /**
     * New public data of the widget, 0-1024 bytes; pass an empty string to remove the public data. If omitted, then the public data isn't changed.
     */
    #[Field('public_data', required: false)]
    public ?string $publicData = null;

    /**
     * New data of the widget, which is specific to the message, 0-1024 bytes; pass an empty string to remove the data. If omitted, then the data isn't changed.
     */
    #[Field('chat_data', required: false)]
    public ?string $chatData = null;

    public function __construct(
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $publicData = null,
        ?string $chatData = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($messageId !== null) $this->messageId = $messageId;
        if ($inlineMessageId !== null) $this->inlineMessageId = $inlineMessageId;
        if ($publicData !== null) $this->publicData = $publicData;
        if ($chatData !== null) $this->chatData = $chatData;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
