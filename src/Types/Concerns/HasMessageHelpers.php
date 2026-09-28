<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

use Tueen\Telegram\Enums\MessageType;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\PaidMediaLivePhoto;
use Tueen\Telegram\Types\PaidMediaPhoto;
use Tueen\Telegram\Types\PaidMediaVideo;
use Tueen\Telegram\Types\PhotoSize;

trait HasMessageHelpers
{
    /**
     * Identifies the specific message content type using PHP 8.4 property hooks.
     */
    public MessageType $type {
        get => $this->resolveMessageType();
    }

    /**
     * Primary file_id of any media attached to this message, or null.
     */
    public ?string $fileId {
        get => $this->findFileId();
    }

    /**
     * Whether this message represents a bot command.
     */
    public bool $isCommand {
        get => $this->isCommand();
    }

    /**
     * Resolves the MessageType enum for this message.
     */
    public function resolveMessageType(): MessageType
    {
        return match (true) {
            $this->text !== null => MessageType::TEXT,
            $this->richMessage !== null => MessageType::RICH_MESSAGE,
            $this->animation !== null => MessageType::ANIMATION,
            $this->audio !== null => MessageType::AUDIO,
            $this->document !== null => MessageType::DOCUMENT,
            $this->photo !== null => MessageType::PHOTO,
            $this->livePhoto !== null => MessageType::LIVE_PHOTO,
            $this->sticker !== null => MessageType::STICKER,
            $this->story !== null => MessageType::STORY,
            $this->video !== null => MessageType::VIDEO,
            $this->videoNote !== null => MessageType::VIDEO_NOTE,
            $this->voice !== null => MessageType::VOICE,
            $this->checklist !== null => MessageType::CHECKLIST,
            $this->contact !== null => MessageType::CONTACT,
            $this->dice !== null => MessageType::DICE,
            $this->game !== null => MessageType::GAME,
            $this->poll !== null => MessageType::POLL,
            $this->venue !== null => MessageType::VENUE,
            $this->location !== null => MessageType::LOCATION,
            $this->paidMedia !== null => MessageType::PAID_MEDIA,
            $this->newChatMembers !== null => MessageType::NEW_CHAT_MEMBERS,
            $this->leftChatMember !== null => MessageType::LEFT_CHAT_MEMBER,
            $this->chatOwnerLeft !== null => MessageType::CHAT_OWNER_LEFT,
            $this->chatOwnerChanged !== null => MessageType::CHAT_OWNER_CHANGED,
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
            $this->gift !== null => MessageType::GIFT,
            $this->uniqueGift !== null => MessageType::UNIQUE_GIFT,
            $this->giftUpgradeSent !== null => MessageType::GIFT_UPGRADE_SENT,
            $this->connectedWebsite !== null => MessageType::CONNECTED_WEBSITE,
            $this->writeAccessAllowed !== null => MessageType::WRITE_ACCESS_ALLOWED,
            $this->passportData !== null => MessageType::PASSPORT_DATA,
            $this->proximityAlertTriggered !== null => MessageType::PROXIMITY_ALERT_TRIGGERED,
            $this->boostAdded !== null => MessageType::BOOST_ADDED,
            $this->chatBackgroundSet !== null => MessageType::CHAT_BACKGROUND_SET,
            $this->checklistTasksDone !== null => MessageType::CHECKLIST_TASKS_DONE,
            $this->checklistTasksAdded !== null => MessageType::CHECKLIST_TASKS_ADDED,
            $this->communityChatAdded !== null => MessageType::COMMUNITY_CHAT_ADDED,
            $this->communityChatJoined !== null => MessageType::COMMUNITY_CHAT_JOINED,
            $this->communityChatRemoved !== null => MessageType::COMMUNITY_CHAT_REMOVED,
            $this->directMessagePriceChanged !== null => MessageType::DIRECT_MESSAGE_PRICE_CHANGED,
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
            $this->managedBotCreated !== null => MessageType::MANAGED_BOT_CREATED,
            $this->paidMessagePriceChanged !== null => MessageType::PAID_MESSAGE_PRICE_CHANGED,
            $this->pollOptionAdded !== null => MessageType::POLL_OPTION_ADDED,
            $this->pollOptionDeleted !== null => MessageType::POLL_OPTION_DELETED,
            $this->suggestedPostApproved !== null => MessageType::SUGGESTED_POST_APPROVED,
            $this->suggestedPostApprovalFailed !== null => MessageType::SUGGESTED_POST_APPROVAL_FAILED,
            $this->suggestedPostDeclined !== null => MessageType::SUGGESTED_POST_DECLINED,
            $this->suggestedPostPaid !== null => MessageType::SUGGESTED_POST_PAID,
            $this->suggestedPostRefunded !== null => MessageType::SUGGESTED_POST_REFUNDED,
            $this->videoChatScheduled !== null => MessageType::VIDEO_CHAT_SCHEDULED,
            $this->videoChatStarted !== null => MessageType::VIDEO_CHAT_STARTED,
            $this->videoChatEnded !== null => MessageType::VIDEO_CHAT_ENDED,
            $this->videoChatParticipantsInvited !== null => MessageType::VIDEO_CHAT_PARTICIPANTS_INVITED,
            $this->webAppData !== null => MessageType::WEB_APP_DATA,
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
     * Checks if this message matches any of the given message types.
     */
    public function isType(MessageType|string ...$types): bool
    {
        foreach ($types as $type) {
            if ($type instanceof MessageType && $this->type === $type) {
                return true;
            }
            if (is_string($type) && $this->type->value === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if this message matches any of the given message types (alias for isType).
     */
    public function isMessage(MessageType|string ...$types): bool
    {
        if (empty($types)) {
            return true;
        }

        return $this->isType(...$types);
    }

    /**
     * Checks if this message is a reply to another message.
     * If an ID or Message object is provided, checks if it specifically replies to that message.
     *
     * @param int|Message|null $messageId Optional specific message ID or Message object to verify
     */
    public function isRepliedToMessage(int|Message|null $messageId = null): bool
    {
        $targetId = $messageId instanceof Message ? $messageId->messageId : $messageId;
        $repliedId = $this->replyToMessage?->messageId ?? $this->externalReply?->messageId;

        if ($targetId === null) {
            return $repliedId !== null;
        }

        return $repliedId === $targetId;
    }

    /**
     * Smart text finder. Returns text, caption, or extracted text from rich formatted message blocks.
     */
    public function findAnyText(): ?string
    {
        if ($this->text !== null && $this->text !== '') {
            return $this->text;
        }

        if ($this->caption !== null && $this->caption !== '') {
            return $this->caption;
        }

        if (isset($this->richMessage) && $this->richMessage !== null) {
            $richText = $this->extractPlainTextFromRich($this->richMessage);
            if ($richText !== null && $richText !== '') {
                return $richText;
            }
        }

        return null;
    }

    /**
     * Alias for findAnyText().
     */
    public function findText(): ?string
    {
        return $this->findAnyText();
    }

    /**
     * Returns the text or caption of the message (backward-compatible alias).
     */
    public function getText(): ?string
    {
        return $this->findAnyText();
    }

    /**
     * Recursively extracts plain text from rich message blocks and rich text nodes.
     */
    private function extractPlainTextFromRich(mixed $node, bool $isBlockLevel = false): ?string
    {
        if ($node === null) {
            return null;
        }

        if (is_string($node)) {
            return $node;
        }

        if (is_array($node)) {
            $parts = [];
            foreach ($node as $item) {
                $extracted = $this->extractPlainTextFromRich($item, false);
                if ($extracted !== null && $extracted !== '') {
                    $parts[] = $extracted;
                }
            }
            return empty($parts) ? null : implode($isBlockLevel ? "\n" : '', $parts);
        }

        if (is_object($node)) {
            if (isset($node->blocks) && is_array($node->blocks)) {
                return $this->extractPlainTextFromRich($node->blocks, true);
            }

            if (isset($node->text)) {
                return $this->extractPlainTextFromRich($node->text, false);
            }

            if (isset($node->caption)) {
                return $this->extractPlainTextFromRich($node->caption, false);
            }
        }

        return null;
    }

    /**
     * Resolves the largest PhotoSize from a given array of PhotoSizes or the message's photos.
     *
     * @param PhotoSize[]|null $photos
     */
    public function findLargestPhoto(?array $photos = null): ?PhotoSize
    {
        $photos ??= $this->photo;

        if (empty($photos)) {
            return null;
        }

        $largest = null;
        $maxDim = -1;

        foreach ($photos as $p) {
            $width = $p instanceof PhotoSize ? $p->width : (is_array($p) ? ($p['width'] ?? 0) : 0);
            $height = $p instanceof PhotoSize ? $p->height : (is_array($p) ? ($p['height'] ?? 0) : 0);
            $dim = $width * $height;

            if ($dim >= $maxDim) {
                $maxDim = $dim;
                $largest = $p;
            }
        }

        return $largest instanceof PhotoSize ? $largest : (is_array($largest) ? new PhotoSize($largest) : null);
    }

    /**
     * Alias for findLargestPhoto().
     *
     * @param PhotoSize[]|null $photos
     */
    public function getLargestPhoto(?array $photos = null): ?PhotoSize
    {
        return $this->findLargestPhoto($photos);
    }

    /**
     * Finds the primary file_id from any media attached to this message.
     * For photos, always resolves the file_id of the largest resolution photo.
     */
    public function findFileId(): ?string
    {
        if (!empty($this->photo)) {
            return $this->findLargestPhoto($this->photo)?->fileId;
        }

        if ($this->animation !== null) {
            return $this->animation->fileId;
        }

        if ($this->video !== null) {
            return $this->video->fileId;
        }

        if ($this->audio !== null) {
            return $this->audio->fileId;
        }

        if ($this->document !== null) {
            return $this->document->fileId;
        }

        if ($this->voice !== null) {
            return $this->voice->fileId;
        }

        if ($this->videoNote !== null) {
            return $this->videoNote->fileId;
        }

        if ($this->sticker !== null) {
            return $this->sticker->fileId;
        }

        if ($this->livePhoto !== null) {
            return $this->livePhoto->fileId;
        }

        if ($this->paidMedia !== null && !empty($this->paidMedia->paidMedia)) {
            foreach ($this->paidMedia->paidMedia as $item) {
                if ($item instanceof PaidMediaPhoto && !empty($item->photo)) {
                    return $this->findLargestPhoto($item->photo)?->fileId;
                }
                if ($item instanceof PaidMediaVideo && isset($item->video)) {
                    return $item->video->fileId;
                }
                if ($item instanceof PaidMediaLivePhoto && isset($item->livePhoto)) {
                    return $item->livePhoto->fileId;
                }
            }
        }

        if (!empty($this->newChatPhoto)) {
            return $this->findLargestPhoto($this->newChatPhoto)?->fileId;
        }

        return null;
    }

    /**
     * Alias for findFileId().
     */
    public function getFileId(): ?string
    {
        return $this->findFileId();
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
