<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\InlineKeyboardMarkup;

/**
 * Use this method to edit the caption of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
 *
 * @link https://core.telegram.org/bots/api#editephemeralmessagecaption
 */
#[ApiMethod('editEphemeralMessageCaption', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class EditEphemeralMessageCaption extends Method
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
     * New caption of the message, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public ?string $caption = null;

    /**
     * Mode for parsing entities in the message caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ?string $parseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
     */
    #[Field('caption_entities', required: false)]
    public ?array $captionEntities = null;

    /**
     * Pass True if the caption must be shown above the message media. Supported only for animation, photo and video messages.
     */
    #[Field('show_caption_above_media', required: false)]
    public ?bool $showCaptionAboveMedia = null;

    /**
     * A JSON-serialized object for an inline keyboard
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?InlineKeyboardMarkup $replyMarkup = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($receiverUserId !== null) $this->receiverUserId = $receiverUserId;
        if ($ephemeralMessageId !== null) $this->ephemeralMessageId = $ephemeralMessageId;
        if ($caption !== null) $this->caption = $caption;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($captionEntities !== null) $this->captionEntities = $captionEntities;
        if ($showCaptionAboveMedia !== null) $this->showCaptionAboveMedia = $showCaptionAboveMedia;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
    }

    public static function make(
        int|string $chatId,
        int $receiverUserId,
        int $ephemeralMessageId,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?InlineKeyboardMarkup $replyMarkup = null
    ): static
    {
        return new static($chatId, $receiverUserId, $ephemeralMessageId, $caption, $parseMode, $captionEntities, $showCaptionAboveMedia, $replyMarkup);
    }
}
