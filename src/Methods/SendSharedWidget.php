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
 * Use this method to send again a shared widget created by the bot. Currently, in the production environment, widgets can be sent only to private chats, and sent widgets automatically have protected content and therefore can't be forwarded. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendsharedwidget
 */
#[ApiMethod('sendSharedWidget', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendSharedWidget extends Method
{
    /**
     * Unique identifier for the target chat or username of the target supergroup in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Unique identifier of the shared widget
     */
    #[Field('widget_id', required: true)]
    public ?string $widgetId = null;

    /**
     * Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

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
        ?string $widgetId = null,
        ?int $messageThreadId = null,
        ?string $chatData = null,
        ?string $caption = null,
        ParseMode|string|null $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
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
        if ($widgetId !== null) $this->widgetId = $widgetId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($chatData !== null) $this->chatData = $chatData;
        if ($caption !== null) $this->caption = $caption;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($captionEntities !== null) $this->captionEntities = $captionEntities;
        if ($showCaptionAboveMedia !== null) $this->showCaptionAboveMedia = $showCaptionAboveMedia;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
