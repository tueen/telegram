<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiErrors;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\ParseMode;
use Tueen\Telegram\Enums\TelegramErrorCode;
use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\ButtonDataInvalidException;
use Tueen\Telegram\Exceptions\CantParseEntitiesException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\MessageTooLongException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Types\EphemeralMessageParameters;
use Tueen\Telegram\Types\ForceReply;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\LinkPreviewOptions;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\SuggestedPostParameters;

/**
 * Use this method to send text messages. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendmessage
 *
 * @throws ChatNotFoundException
 * @throws MessageTooLongException
 * @throws CantParseEntitiesException
 * @throws ButtonDataInvalidException
 * @throws BotBlockedException
 * @throws RateLimitException
 * @throws ApiException
 */
#[ApiMethod('sendMessage', 'POST')]
#[ReturnType(Message::class, isArray: false)]
#[ApiErrors([TelegramErrorCode::ChatNotFound, TelegramErrorCode::MessageTooLong, TelegramErrorCode::CantParseEntities, TelegramErrorCode::ButtonDataInvalid, TelegramErrorCode::BotBlocked, TelegramErrorCode::FloodWait])]
class SendMessage extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Text of the message to be sent, 1-4096 characters after entities parsing
     */
    #[Field('text', required: true)]
    public ?string $text = null;

    /**
     * Unique identifier of the business connection on behalf of which the message will be sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    /**
     * Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     */
    #[Field('direct_messages_topic_id', required: false)]
    public ?int $directMessagesTopicId = null;

    /**
     * A JSON-serialized object containing the parameters of the ephemeral message to send
     */
    #[Field('ephemeral_message_parameters', required: false)]
    public ?EphemeralMessageParameters $ephemeralMessageParameters = null;

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
     * A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     */
    #[Field('suggested_post_parameters', required: false)]
    public ?SuggestedPostParameters $suggestedPostParameters = null;

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
        ?string $text = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?EphemeralMessageParameters $ephemeralMessageParameters = null,
        ParseMode|string|null $parseMode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $linkPreviewOptions = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
        ?ReplyParameters $replyParameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($text !== null) $this->text = $text;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($directMessagesTopicId !== null) $this->directMessagesTopicId = $directMessagesTopicId;
        if ($ephemeralMessageParameters !== null) $this->ephemeralMessageParameters = $ephemeralMessageParameters;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($entities !== null) $this->entities = $entities;
        if ($linkPreviewOptions !== null) $this->linkPreviewOptions = $linkPreviewOptions;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($suggestedPostParameters !== null) $this->suggestedPostParameters = $suggestedPostParameters;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
