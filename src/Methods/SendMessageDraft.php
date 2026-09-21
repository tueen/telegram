<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Use this method to stream a partial message to a user while the message is being generated. Note that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is finalized, you must call sendMessage with the complete message to persist it in the user's chat. Returns True on success.
 *
 * @link https://core.telegram.org/bots/api#sendmessagedraft
 */
#[ApiMethod('sendMessageDraft', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class SendMessageDraft extends Method
{
    /**
     * Unique identifier for the target private chat
     */
    #[Field('chat_id', required: true)]
    public int $chatId;

    /**
     * Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation.
     */
    #[Field('draft_id', required: true)]
    public int $draftId;

    /**
     * Unique identifier for the target message thread
     */
    #[Field('message_thread_id', required: false)]
    public ?int $messageThreadId = null;

    /**
     * Text of the message to be sent, 0-4096 characters after entities parsing. Pass an empty text to show a "Thinking..." placeholder.
     */
    #[Field('text', required: false)]
    public ?string $text = null;

    /**
     * Mode for parsing entities in the message text. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    public ?string $parseMode = null;

    /**
     * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
     */
    #[Field('entities', required: false)]
    public ?array $entities = null;

    /**
     * Pass True to show the user a button to stop further drafts. The bot will receive an Update "stopped_message_generation" if the user presses the button.
     */
    #[Field('can_stop', required: false)]
    public ?bool $canStop = null;

    /**
     * Pass True to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
     */
    #[Field('keep_on_stop', required: false)]
    public ?bool $keepOnStop = null;

    public function __construct(
        int $chatId,
        int $draftId,
        ?int $messageThreadId = null,
        ?string $text = null,
        ?string $parseMode = null,
        ?array $entities = null,
        ?bool $canStop = null,
        ?bool $keepOnStop = null
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($draftId !== null) $this->draftId = $draftId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($text !== null) $this->text = $text;
        if ($parseMode !== null) $this->parseMode = $parseMode;
        if ($entities !== null) $this->entities = $entities;
        if ($canStop !== null) $this->canStop = $canStop;
        if ($keepOnStop !== null) $this->keepOnStop = $keepOnStop;
    }

    public static function make(
        int $chatId,
        int $draftId,
        ?int $messageThreadId = null,
        ?string $text = null,
        ?string $parseMode = null,
        ?array $entities = null,
        ?bool $canStop = null,
        ?bool $keepOnStop = null
    ): static
    {
        return new static($chatId, $draftId, $messageThreadId, $text, $parseMode, $entities, $canStop, $keepOnStop);
    }
}
