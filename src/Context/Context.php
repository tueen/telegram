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
    public function __construct(
        public readonly Update $update,
        public readonly TelegramClient $client,
        public readonly ?Telegram $bot = null
    ) {}

    public function chatId(): ?int
    {
        return $this->update->findChatId();
    }

    public function userId(): ?int
    {
        return $this->update->findUserId();
    }

    public function messageId(): ?int
    {
        return $this->update->findMessageId();
    }

    public function businessConnectionId(): ?string
    {
        return $this->update->findBusinessConnectionId();
    }

    public function messageThreadId(): ?int
    {
        return $this->update->findMessageThreadId();
    }

    public function inlineMessageId(): ?string
    {
        return $this->update->findInlineMessageId();
    }

    public function callbackQueryId(): ?string
    {
        return $this->update->findCallbackQueryId();
    }

    public function inlineQueryId(): ?string
    {
        return $this->update->findInlineQueryId();
    }

    public function shippingQueryId(): ?string
    {
        return $this->update->findShippingQueryId();
    }

    public function preCheckoutQueryId(): ?string
    {
        return $this->update->findPreCheckoutQueryId();
    }

    public function directMessagesTopicId(): ?int
    {
        return $this->update->findDirectMessagesTopicId();
    }

    public function guestQueryId(): ?string
    {
        return $this->update->findGuestQueryId();
    }

    public function user(): ?User
    {
        return $this->update->findUser();
    }

    public function chat(): ?Chat
    {
        return $this->update->findChat();
    }

    public function message(): ?Message
    {
        return $this->update->findMessage();
    }

    /**
     * Replies directly to the current chat (and active message thread if applicable).
     */
    public function reply(string|Text $text, mixed ...$args): mixed
    {
        $chatId = $this->chatId();
        if ($chatId === null) {
            throw new \Tueen\Telegram\Exceptions\TelegramException("Cannot reply: unable to determine chat_id from current context.");
        }

        $params = ['chatId' => $chatId, 'text' => $text];

        $threadId = $this->messageThreadId();
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
            return $this->bot->flow($this->chatId(), $this->userId(), $this->update);
        }

        throw new \Tueen\Telegram\Exceptions\TelegramException("Flow session requires active Telegram bot instance.");
    }

    public function flowBack(?string $replyMessage = null): bool
    {
        if ($this->bot !== null) {
            return $this->bot->flowBack($this->chatId(), $this->userId(), $replyMessage, $this->update);
        }

        return false;
    }

    public function cancelFlow(?string $replyMessage = 'Operation cancelled.'): bool
    {
        if ($this->bot !== null) {
            return $this->bot->cancelFlow($this->chatId(), $this->userId(), $replyMessage, $this->update);
        }

        return false;
    }

    public function finishFlow(): bool
    {
        if ($this->bot !== null) {
            return $this->bot->finishFlow($this->chatId(), $this->userId(), $this->update);
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
