<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\EphemeralMessageParameters;
use Tueen\Telegram\Types\ForceReply;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\ReplyKeyboardMarkup;
use Tueen\Telegram\Types\ReplyKeyboardRemove;
use Tueen\Telegram\Types\ReplyParameters;
use Tueen\Telegram\Types\SuggestedPostParameters;

/**
 * Use this method to send a rounded square MPEG4 video of up to 1 minute long. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendvideonote
 */
#[ApiMethod('sendVideoNote', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendVideoNote extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Video note to send. Pass a file_id as String to send a video note that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. More information on Sending Files: https://core.telegram.org/bots/api#sending-files. Sending video notes by a URL is currently unsupported.
     */
    #[Field('video_note', required: true)]
    #[RequiresUpload]
    public InputFile|string|null $videoNote = null;

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
     * Duration of sent video in seconds
     */
    #[Field('duration', required: false)]
    public ?int $duration = null;

    /**
     * Video width and height, i.e. diameter of the video message
     */
    #[Field('length', required: false)]
    public ?int $length = null;

    /**
     * Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('thumbnail', required: false)]
    #[RequiresUpload]
    public InputFile|string|null $thumbnail = null;

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
        InputFile|string|null $videoNote = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?EphemeralMessageParameters $ephemeralMessageParameters = null,
        ?int $duration = null,
        ?int $length = null,
        InputFile|string|null $thumbnail = null,
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
        if ($videoNote !== null) $this->videoNote = $videoNote;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($directMessagesTopicId !== null) $this->directMessagesTopicId = $directMessagesTopicId;
        if ($ephemeralMessageParameters !== null) $this->ephemeralMessageParameters = $ephemeralMessageParameters;
        if ($duration !== null) $this->duration = $duration;
        if ($length !== null) $this->length = $length;
        if ($thumbnail !== null) $this->thumbnail = $thumbnail;
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
