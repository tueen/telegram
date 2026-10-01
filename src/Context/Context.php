<?php

declare(strict_types=1);

namespace Tueen\Telegram\Context;

use Tueen\Telegram\Client\TelegramClient;
use Tueen\Telegram\Flow\FlowSession;
use Tueen\Telegram\Formatting\Text;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;

/**
 * Scoped, immutable request context representing a single Update event.
 * Eliminates global mutable state and guarantees thread-safe, concurrent handling.
 */
class Context
{
    public ?int $chatId {
        get => $this->update->findChatId();
    }

    public ?int $userId {
        get => $this->update->findUserId();
    }

    public ?int $messageId {
        get => $this->update->findMessageId();
    }

    public ?string $businessConnectionId {
        get => $this->update->findBusinessConnectionId();
    }

    public ?int $messageThreadId {
        get => $this->update->findMessageThreadId();
    }

    public ?string $inlineMessageId {
        get => $this->update->findInlineMessageId();
    }

    public ?string $callbackQueryId {
        get => $this->update->findCallbackQueryId();
    }

    public ?string $inlineQueryId {
        get => $this->update->findInlineQueryId();
    }

    public ?string $shippingQueryId {
        get => $this->update->findShippingQueryId();
    }

    public ?string $preCheckoutQueryId {
        get => $this->update->findPreCheckoutQueryId();
    }

    public ?int $directMessagesTopicId {
        get => $this->update->findDirectMessagesTopicId();
    }

    public ?string $guestQueryId {
        get => $this->update->findGuestQueryId();
    }

    public ?User $user {
        get => $this->update->findUser();
    }

    public ?Chat $chat {
        get => $this->update->findChat();
    }

    public ?Message $message {
        get => $this->update->findMessage();
    }

    public function __construct(
        public readonly Update $update,
        public readonly TelegramClient $client,
        public readonly ?Telegram $bot = null
    ) {}


    /**
     * Replies directly to the current chat (and active message thread if applicable).
     */
    public function reply(string|Text $text, mixed ...$args): mixed
    {
        $chatId = $this->chatId;
        if ($chatId === null) {
            throw new \Tueen\Telegram\Exceptions\TelegramException("Cannot reply: unable to determine chat_id from current context.");
        }

        $params = ['chatId' => $chatId, 'text' => $text];

        $threadId = $this->messageThreadId;
        if ($threadId !== null) {
            $params['messageThreadId'] = $threadId;
        }

        foreach ($args as $k => $v) {
            if (is_array($v) && is_int($k)) {
                $params = array_merge($params, $v);
            } else {
                $params[$k] = $v;
            }
        }

        if ($this->bot !== null) {
            return $this->bot->sendMessage($params);
        }

        return $this->client->sendMessage($params);
    }

    /**
     * Resolves a fluent FlowSession for this update context.
     */
    public function flow(): FlowSession
    {
        if ($this->bot !== null) {
            return $this->bot->flow($this->chatId, $this->userId, $this->update);
        }

        throw new \Tueen\Telegram\Exceptions\TelegramException("Flow session requires active Telegram bot instance.");
    }

    public function flowBack(?string $replyMessage = null): bool
    {
        if ($this->bot !== null) {
            return $this->bot->flowBack($this->chatId, $this->userId, $replyMessage, $this->update);
        }

        return false;
    }

    public function cancelFlow(?string $replyMessage = 'Operation cancelled.'): bool
    {
        if ($this->bot !== null) {
            return $this->bot->cancelFlow($this->chatId, $this->userId, $replyMessage, $this->update);
        }

        return false;
    }

    public function finishFlow(): bool
    {
        if ($this->bot !== null) {
            return $this->bot->finishFlow($this->chatId, $this->userId, $this->update);
        }

        return false;
    }

    /**
     * Proxies method calls to client or bot.
     */
    public function __call(string $name, array $arguments): mixed
    {
        if ($this->bot !== null && method_exists($this->bot, $name)) {
            return $this->bot->$name(...$arguments);
        }

        return $this->client->$name(...$arguments);
    }
}
