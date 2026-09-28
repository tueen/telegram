<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\ReplyParameters;

/**
 * Use this method to send a game. On success, the sent Message is returned.
 *
 * @link https://core.telegram.org/bots/api#sendgame
 */
#[ApiMethod('sendGame', 'POST')]
#[ReturnType(Message::class, isArray: false)]
class SendGame extends Method
{
    /**
     * Unique identifier for the target chat or username of the target bot in the format @username. Games can't be sent to channel direct messages chats and channel chats.
     */
    #[Field('chat_id', required: true)]
    public int|string|null $chatId = null;

    /**
     * Short name of the game, serves as the unique identifier for the game. Set up your games via @BotFather.
     */
    #[Field('game_short_name', required: true)]
    public ?string $gameShortName = null;

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
     * A JSON-serialized object for an inline keyboard. If empty, one 'Play game_title' button will be shown. If not empty, the first button must launch the game.
     */
    #[Field('reply_markup', required: false)]
    public ?InlineKeyboardMarkup $replyMarkup = null;

    public function __construct(
        int|string|null $chatId = null,
        ?string $gameShortName = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        ?InlineKeyboardMarkup $replyMarkup = null,
        mixed ...$extra
    )
    {
        if ($chatId !== null) $this->chatId = $chatId;
        if ($gameShortName !== null) $this->gameShortName = $gameShortName;
        if ($businessConnectionId !== null) $this->businessConnectionId = $businessConnectionId;
        if ($messageThreadId !== null) $this->messageThreadId = $messageThreadId;
        if ($disableNotification !== null) $this->disableNotification = $disableNotification;
        if ($protectContent !== null) $this->protectContent = $protectContent;
        if ($allowPaidBroadcast !== null) $this->allowPaidBroadcast = $allowPaidBroadcast;
        if ($messageEffectId !== null) $this->messageEffectId = $messageEffectId;
        if ($replyParameters !== null) $this->replyParameters = $replyParameters;
        if ($replyMarkup !== null) $this->replyMarkup = $replyMarkup;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
