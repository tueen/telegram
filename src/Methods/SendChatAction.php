<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Enums\ChatAction;

/**
 * Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.
 * We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 *
 * @link https://core.telegram.org/bots/api#sendchataction
 */
#[ApiMethod('sendChatAction', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SendChatAction extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot or supergroup in the format @username. Channel chats and channel direct messages chats aren't supported.
     */
    #[Field('chat_id', required: true)]
    public int|string $chatId;

    /**
     * Type of action to broadcast. Choose one, depending on what the user is about to receive: typing for text messages, upload_photo for photos, record_video or upload_video for videos, record_voice or upload_voice for voice notes, upload_document for general files, choose_sticker for stickers, find_location for location data, record_video_note or upload_video_note for video notes.
     */
    #[Field('action', required: true)]
    public ChatAction|string $action;

    /**
     * Unique identifier of the business connection on behalf of which the action will be sent
     */
    #[Field('business_connection_id', required: false)]
    public ?string $businessConnectionId = null;

    /**
     * Unique identifier for the target message thread or topic of a forum; for supergroups and private chats of bots with forum topic mode enabled only
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    public function __construct(
        int|string $chatId,
        ChatAction|string $action,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($action !== null) $this->action = $action;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
    }
}
