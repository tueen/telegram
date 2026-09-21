<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\User;

trait HasUpdateHelpers
{
    /**
     * Identifies the specific update type using PHP 8.4 property hooks.
     */
    public UpdateType $type {
        get => $this->resolveUpdateType();
    }

    /**
     * Primary file_id of any media in the contained message, or null.
     */
    public ?string $fileId {
        get => $this->findFileId();
    }

    /**
     * Resolves the UpdateType enum for this update.
     */
    public function resolveUpdateType(): UpdateType
    {
        return match (true) {
            $this->message !== null => UpdateType::MESSAGE,
            $this->editedMessage !== null => UpdateType::EDITED_MESSAGE,
            $this->channelPost !== null => UpdateType::CHANNEL_POST,
            $this->editedChannelPost !== null => UpdateType::EDITED_CHANNEL_POST,
            $this->businessConnection !== null => UpdateType::BUSINESS_CONNECTION,
            $this->businessMessage !== null => UpdateType::BUSINESS_MESSAGE,
            $this->editedBusinessMessage !== null => UpdateType::EDITED_BUSINESS_MESSAGE,
            $this->deletedBusinessMessages !== null => UpdateType::DELETED_BUSINESS_MESSAGES,
            $this->messageReaction !== null => UpdateType::MESSAGE_REACTION,
            $this->messageReactionCount !== null => UpdateType::MESSAGE_REACTION_COUNT,
            $this->inlineQuery !== null => UpdateType::INLINE_QUERY,
            $this->chosenInlineResult !== null => UpdateType::CHOSEN_INLINE_RESULT,
            $this->callbackQuery !== null => UpdateType::CALLBACK_QUERY,
            $this->shippingQuery !== null => UpdateType::SHIPPING_QUERY,
            $this->preCheckoutQuery !== null => UpdateType::PRE_CHECKOUT_QUERY,
            $this->purchasedPaidMedia !== null => UpdateType::PURCHASED_PAID_MEDIA,
            $this->poll !== null => UpdateType::POLL,
            $this->pollAnswer !== null => UpdateType::POLL_ANSWER,
            $this->myChatMember !== null => UpdateType::MY_CHAT_MEMBER,
            $this->chatMember !== null => UpdateType::CHAT_MEMBER,
            $this->chatJoinRequest !== null => UpdateType::CHAT_JOIN_REQUEST,
            $this->chatBoost !== null => UpdateType::CHAT_BOOST,
            $this->removedChatBoost !== null => UpdateType::REMOVED_CHAT_BOOST,
            isset($this->managedBot) && $this->managedBot !== null => UpdateType::MANAGED_BOT,
            isset($this->botSubscription) && $this->botSubscription !== null => UpdateType::BOT_SUBSCRIPTION,
            isset($this->messageGenerationStopped) && $this->messageGenerationStopped !== null => UpdateType::MESSAGE_GENERATION_STOPPED,
            default => UpdateType::UNKNOWN,
        };
    }

    /**
     * Returns the update type.
     */
    public function getType(): UpdateType
    {
        return $this->type;
    }

    /**
     * Checks if this update matches any of the given update types.
     */
    public function isType(UpdateType|string ...$types): bool
    {
        foreach ($types as $type) {
            if ($type instanceof UpdateType && $this->type === $type) {
                return true;
            }
            if (is_string($type) && $this->type->value === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Smart message finder. Returns the primary message from any update kind.
     */
    public function findMessage(): ?Message
    {
        $msg = $this->message
            ?? $this->editedMessage
            ?? $this->channelPost
            ?? $this->editedChannelPost
            ?? $this->businessMessage
            ?? $this->editedBusinessMessage
            ?? $this->callbackQuery?->message
            ?? null;

        return $msg instanceof Message ? $msg : null;
    }

    /**
     * Smart user finder. Extracts the acting User from any update kind.
     */
    public function findUser(): ?User
    {
        $user = $this->message?->from
            ?? $this->editedMessage?->from
            ?? $this->businessMessage?->from
            ?? $this->editedBusinessMessage?->from
            ?? $this->callbackQuery?->from
            ?? $this->inlineQuery?->from
            ?? $this->chosenInlineResult?->from
            ?? $this->shippingQuery?->from
            ?? $this->preCheckoutQuery?->from
            ?? $this->pollAnswer?->user
            ?? $this->myChatMember?->from
            ?? $this->chatMember?->from
            ?? $this->chatJoinRequest?->from
            ?? $this->messageReaction?->user
            ?? $this->chatBoost?->boost?->source?->user
            ?? null;

        return $user instanceof User ? $user : null;
    }

    /**
     * Smart chat finder. Extracts the target Chat from any update kind.
     */
    public function findChat(): ?Chat
    {
        $chat = $this->message?->chat
            ?? $this->editedMessage?->chat
            ?? $this->channelPost?->chat
            ?? $this->editedChannelPost?->chat
            ?? $this->businessMessage?->chat
            ?? $this->editedBusinessMessage?->chat
            ?? $this->callbackQuery?->message?->chat
            ?? $this->myChatMember?->chat
            ?? $this->chatMember?->chat
            ?? $this->chatJoinRequest?->chat
            ?? $this->messageReaction?->chat
            ?? $this->chatBoost?->chat
            ?? $this->removedChatBoost?->chat
            ?? null;

        return $chat instanceof Chat ? $chat : null;
    }

    /**
     * Smart user ID finder. Extracts the acting user's ID from any update kind.
     */
    public function findUserId(): ?int
    {
        return $this->findUser()?->id;
    }

    /**
     * Smart chat ID finder. Extracts the target chat's ID from any update kind.
     */
    public function findChatId(): ?int
    {
        return $this->findChat()?->id;
    }

    /**
     * Smart message ID finder. Extracts the primary message's ID from any update kind.
     */
    public function findMessageId(): ?int
    {
        return $this->findMessage()?->messageId;
    }

    /**
     * @deprecated Use findUserId() instead.
     */
    public function getUserId(): ?int
    {
        return $this->findUserId();
    }

    /**
     * @deprecated Use findChatId() instead.
     */
    public function getChatId(): ?int
    {
        return $this->findChatId();
    }

    /**
     * @deprecated Use findMessageId() instead.
     */
    public function getMessageId(): ?int
    {
        return $this->findMessageId();
    }

    /**
     * Finds the primary file_id from the contained message, if available.
     */
    public function findFileId(): ?string
    {
        return $this->findMessage()?->findFileId();
    }

    /**
     * Alias for findFileId().
     */
    public function getFileId(): ?string
    {
        return $this->findFileId();
    }

    /**
     * @deprecated Use findMessage() instead.
     */
    public function getMessage(): ?Message
    {
        return $this->findMessage();
    }

    /**
     * @deprecated Use findUser() instead.
     */
    public function getUser(): ?User
    {
        return $this->findUser();
    }

    /**
     * @deprecated Use findChat() instead.
     */
    public function getChat(): ?Chat
    {
        return $this->findChat();
    }
}
