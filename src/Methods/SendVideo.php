<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Enums\ParseMode;
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
 * Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 *
 * @link https://core.telegram.org/bots/api#sendvideo
 */
#[ApiMethod('sendVideo', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendVideo extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot, supergroup or channel in the format @username
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a video from the Internet, or upload a new video using multipart/form-data. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('video', required: true)]
    #[RequiresUpload]
    public InputFile|string|null $video = null;

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
     * Video width
     */
    #[Field('width', required: false)]
    public ?int $width = null;

    /**
     * Video height
     */
    #[Field('height', required: false)]
    public ?int $height = null;

    /**
     * Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('thumbnail', required: false)]
    #[RequiresUpload]
    public InputFile|string|null $thumbnail = null;

    /**
     * Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files: https://core.telegram.org/bots/api#sending-files
     */
    #[Field('cover', required: false)]
    #[RequiresUpload]
    public InputFile|string|null $cover = null;

    /**
     * Start timestamp for the video in the message
     */
    #[Field('start_timestamp', required: false)]
    public ?int $startTimestamp = null;

    /**
     * Video caption (may also be used when resending videos by file_id), 0-1024 characters after entities parsing
     */
    #[Field('caption', required: false)]
    public ?string $caption = null;

    /**
     * Mode for parsing entities in the video caption. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ParseMode|string|null $parseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
     */
    #[Field('caption_entities', required: false)]
    public ?array $captionEntities = null;

    /**
     * Pass True if the caption must be shown above the message media
     */
    #[Field('show_caption_above_media', required: false)]
    public ?bool $showCaptionAboveMedia = null;

    /**
     * Pass True if the video needs to be covered with a spoiler animation
     */
    #[Field('has_spoiler', required: false)]
    public ?bool $hasSpoiler = null;

    /**
     * Pass True if the uploaded video is suitable for streaming
     */
    #[Field('supports_streaming', required: false)]
    public ?bool $supportsStreaming = null;

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
        InputFile|string|null $video = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $directMessagesTopicId = null,
        ?EphemeralMessageParameters $ephemeralMessageParameters = null,
        ?int $duration = null,
        ?int $width = null,
        ?int $height = null,
        InputFile|string|null $thumbnail = null,
        InputFile|string|null $cover = null,
        ?int $startTimestamp = null,
        ?string $caption = null,
        ParseMode|string|null $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $hasSpoiler = null,
        ?bool $supportsStreaming = null,
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
        if ($video !== null) $this->video = $video;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($directMessagesTopicId !== null) $this->directMessagesTopicId = $directMessagesTopicId;
        if ($ephemeralMessageParameters !== null) $this->ephemeralMessageParameters = $ephemeralMessageParameters;
        if ($duration !== null) $this->duration = $duration;
        if ($width !== null) $this->width = $width;
        if ($height !== null) $this->height = $height;
        if ($thumbnail !== null) $this->thumbnail = $thumbnail;
        if ($cover !== null) $this->cover = $cover;
        if ($startTimestamp !== null) $this->startTimestamp = $startTimestamp;
        if ($caption !== null) $this->caption = $caption;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($captionEntities !== null) $this->captionEntities = $captionEntities;
        if ($showCaptionAboveMedia !== null) $this->showCaptionAboveMedia = $showCaptionAboveMedia;
        if ($hasSpoiler !== null) $this->hasSpoiler = $hasSpoiler;
        if ($supportsStreaming !== null) $this->supportsStreaming = $supportsStreaming;
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
