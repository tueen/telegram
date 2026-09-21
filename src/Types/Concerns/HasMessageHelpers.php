<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

use Tueen\Telegram\Enums\MessageType;

trait HasMessageHelpers
{
    /**
     * Identifies the specific message content type using PHP 8.4 property hooks.
     */
    public MessageType $type {
        get => $this->resolveMessageType();
    }

    /**
     * Resolves the MessageType enum for this message.
     */
    public function resolveMessageType(): MessageType
    {
        return match (true) {
            $this->text !== null => MessageType::TEXT,
            $this->animation !== null => MessageType::ANIMATION,
            $this->audio !== null => MessageType::AUDIO,
            $this->document !== null => MessageType::DOCUMENT,
            $this->photo !== null => MessageType::PHOTO,
            $this->sticker !== null => MessageType::STICKER,
            $this->story !== null => MessageType::STORY,
            $this->video !== null => MessageType::VIDEO,
            $this->videoNote !== null => MessageType::VIDEO_NOTE,
            $this->voice !== null => MessageType::VOICE,
            $this->contact !== null => MessageType::CONTACT,
            $this->dice !== null => MessageType::DICE,
            $this->game !== null => MessageType::GAME,
            $this->poll !== null => MessageType::POLL,
            $this->venue !== null => MessageType::VENUE,
            $this->location !== null => MessageType::LOCATION,
            $this->newChatMembers !== null => MessageType::NEW_CHAT_MEMBERS,
            $this->leftChatMember !== null => MessageType::LEFT_CHAT_MEMBER,
            $this->newChatTitle !== null => MessageType::NEW_CHAT_TITLE,
            $this->newChatPhoto !== null => MessageType::NEW_CHAT_PHOTO,
            $this->deleteChatPhoto !== null => MessageType::DELETE_CHAT_PHOTO,
            $this->groupChatCreated !== null => MessageType::GROUP_CHAT_CREATED,
            $this->supergroupChatCreated !== null => MessageType::SUPERGROUP_CHAT_CREATED,
            $this->channelChatCreated !== null => MessageType::CHANNEL_CHAT_CREATED,
            $this->messageAutoDeleteTimerChanged !== null => MessageType::MESSAGE_AUTO_DELETE_TIMER_CHANGED,
            $this->migrateToChatId !== null => MessageType::MIGRATE_TO_CHAT_ID,
            $this->migrateFromChatId !== null => MessageType::MIGRATE_FROM_CHAT_ID,
            $this->pinnedMessage !== null => MessageType::PINNED_MESSAGE,
            $this->invoice !== null => MessageType::INVOICE,
            $this->successfulPayment !== null => MessageType::SUCCESSFUL_PAYMENT,
            $this->refundedPayment !== null => MessageType::REFUNDED_PAYMENT,
            $this->usersShared !== null => MessageType::USERS_SHARED,
            $this->chatShared !== null => MessageType::CHAT_SHARED,
            $this->connectedWebsite !== null => MessageType::CONNECTED_WEBSITE,
            $this->writeAccessAllowed !== null => MessageType::WRITE_ACCESS_ALLOWED,
            $this->passportData !== null => MessageType::PASSPORT_DATA,
            $this->proximityAlertTriggered !== null => MessageType::PROXIMITY_ALERT_TRIGGERED,
            $this->boostAdded !== null => MessageType::BOOST_ADDED,
            $this->chatBackgroundSet !== null => MessageType::CHAT_BACKGROUND_SET,
            $this->forumTopicCreated !== null => MessageType::FORUM_TOPIC_CREATED,
            $this->forumTopicEdited !== null => MessageType::FORUM_TOPIC_EDITED,
            $this->forumTopicClosed !== null => MessageType::FORUM_TOPIC_CLOSED,
            $this->forumTopicReopened !== null => MessageType::FORUM_TOPIC_REOPENED,
            $this->generalForumTopicHidden !== null => MessageType::GENERAL_FORUM_TOPIC_HIDDEN,
            $this->generalForumTopicUnhidden !== null => MessageType::GENERAL_FORUM_TOPIC_UNHIDDEN,
            $this->giveawayCreated !== null => MessageType::GIVEAWAY_CREATED,
            $this->giveaway !== null => MessageType::GIVEAWAY,
            $this->giveawayWinners !== null => MessageType::GIVEAWAY_WINNERS,
            $this->giveawayCompleted !== null => MessageType::GIVEAWAY_COMPLETED,
            $this->videoChatScheduled !== null => MessageType::VIDEO_CHAT_SCHEDULED,
            $this->videoChatStarted !== null => MessageType::VIDEO_CHAT_STARTED,
            $this->videoChatEnded !== null => MessageType::VIDEO_CHAT_ENDED,
            $this->videoChatParticipantsInvited !== null => MessageType::VIDEO_CHAT_PARTICIPANTS_INVITED,
            $this->webAppData !== null => MessageType::WEB_APP_DATA,
            $this->paidMedia !== null => MessageType::PAID_MEDIA,
            default => MessageType::UNKNOWN,
        };
    }

    /**
     * Returns the message type.
     */
    public function getType(): MessageType
    {
        return $this->type;
    }

    /**
     * Checks if this message matches a specific type.
     */
    public function isType(MessageType $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Returns the text or caption of the message.
     */
    public function getText(): ?string
    {
        return $this->text ?? $this->caption ?? null;
    }

    /**
     * Checks whether this message is a bot command (e.g. starts with '/').
     */
    public function isCommand(): bool
    {
        if (empty($this->text)) {
            return false;
        }

        if (str_starts_with(trim($this->text), '/')) {
            return true;
        }

        if ($this->entities !== null) {
            foreach ($this->entities as $entity) {
                if ($entity->type?->value === 'bot_command' && $entity->offset === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Extracts the clean command name without slash or @botusername.
     * E.g. '/start@my_bot 123' -> 'start'
     */
    public function getCommand(): ?string
    {
        if (!$this->isCommand()) {
            return null;
        }

        $trimmed = ltrim(trim((string)$this->text), '/');
        $parts = preg_split('/\s+/', $trimmed, 2);
        $command = $parts[0] ?? '';

        // Strip @botusername
        if (str_contains($command, '@')) {
            $command = explode('@', $command, 2)[0];
        }

        return !empty($command) ? $command : null;
    }

    /**
     * Extracts command arguments as an array.
     * E.g. '/ban 12345 spamming' -> ['12345', 'spamming']
     *
     * @return list<string>
     */
    public function getArgs(): array
    {
        if (!$this->isCommand()) {
            return [];
        }

        $trimmed = ltrim(trim((string)$this->text), '/');
        $parts = preg_split('/\s+/', $trimmed, 2);

        if (!isset($parts[1]) || trim($parts[1]) === '') {
            return [];
        }

        return array_values(array_filter(preg_split('/\s+/', trim($parts[1]))));
    }
}
