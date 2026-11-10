<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Types\ForceReply;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\ReplyParameters;

/**
 * Use this method to send a message with an HTML widget. Currently, in the production environment, widgets can be sent only to private chats, and sent widgets automatically have protected content and therefore can't be forwarded. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendwidget
 */
#[ApiMethod('sendWidget', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendWidget extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level.
     */
    #[Field('html', required: true)]
    public ?string $html = null;

    /**
     * Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    /**
     * Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble.
     */
    #[Field('width', required: false)]
    public ?int $width = null;

    /**
     * Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600.
     */
    #[Field('height', required: false)]
    public ?int $height = null;

    /**
     * Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data.
     */
    #[Field('public_data', required: false)]
    public ?string $publicData = null;

    /**
     * Data of the widget, which is specific to the message, 0-1024 bytes
     */
    #[Field('chat_data', required: false)]
    public ?string $chatData = null;

    /**
     * Widget caption, 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public ?string $caption = null;

    /**
     * Mode for parsing entities in the widget caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ParseMode|string|null $parseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
     */
    #[Field('caption_entities', required: false)]
    public ?array $captionEntities = null;

    /**
     * Pass True if the caption must be shown above the widget
     */
    #[Field('show_caption_above_media', required: false)]
    public ?bool $showCaptionAboveMedia = null;

    /**
     * Pass True to allow the widget to send queries to the bot
     */
    #[Field('allow_queries', required: false)]
    public ?bool $allowQueries = null;

    /**
     * Pass True to create a shared widget, which can be sent again using the method sendSharedWidget
     */
    #[Field('is_shared', required: false)]
    public ?bool $isShared = null;

    /**
     * Sends the message silently. Users will receive a notification with no sound.
     */
    #[Field('disable_notification', required: false)]
    public ?bool $disableNotification = null;

    /**
     * Protects the contents of the sent message from forwarding and saving
     */
    #[Field('protect_content', required: false)]
    public ?bool $protectContent = null;

    /**
     * Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     */
    #[Field('allow_paid_broadcast', required: false)]
    public ?bool $allowPaidBroadcast = null;

    /**
     * Unique identifier of the message effect to be added to the message; for private chats only
     */
    #[Field('message_effect_id', required: false)]
    public ?string $messageEffectId = null;

    /**
     * Description of the message to reply to
     */
    #[Field('reply_parameters', required: false)]
    public ?ReplyParameters $replyParameters = null;

    /**
     * Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
     */
    #[Field('reply_markup', required: false)]
    public InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $html = null,
        ?int $messageThreadId = null,
        ?int $width = null,
        ?int $height = null,
        ?string $publicData = null,
        ?string $chatData = null,
        ?string $caption = null,
        ParseMode|string|null $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $allowQueries = null,
        ?bool $isShared = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($html !== null) $this->html = $html;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($width !== null) $this->width = $width;
        if ($height !== null) $this->height = $height;
        if ($publicData !== null) $this->publicData = $publicData;
        if ($chatData !== null) $this->chatData = $chatData;
        if ($caption !== null) $this->caption = $caption;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($captionEntities !== null) $this->captionEntities = $captionEntities;
        if ($showCaptionAboveMedia !== null) $this->showCaptionAboveMedia = $showCaptionAboveMedia;
        if ($allowQueries !== null) $this->allowQueries = $allowQueries;
        if ($isShared !== null) $this->isShared = $isShared;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
