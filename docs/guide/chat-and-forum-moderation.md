# Chat & Forum Moderation

Telegram provides extensive administrative and moderation APIs for groups, supergroups, forums, and channels. `tueen/telegram` provides full typed support for member moderation, permissions, forum topic organization, and join requests.

---

## 1. Member Moderation

### Banning & Unbanning Users

```php
use Tueen\Telegram\Telegram;

$telegram = new Telegram('YOUR_BOT_TOKEN');

// Ban user permanently and revoke their recent messages:
$telegram->banChatMember(
    chatId: -1001234567890,
    userId: 987654321,
    revokeMessages: true
);

// Temporary ban (e.g. 24 hours):
$telegram->banChatMember(
    chatId: -1001234567890,
    userId: 987654321,
    untilDate: time() + 86400
);

// Unban user (allows them to rejoin):
$telegram->unbanChatMember(
    chatId: -1001234567890,
    userId: 987654321,
    onlyIfBanned: true
);
```

### Restricting Members (Muting & Permissions)

Restrict specific rights (e.g. disable sending media or links):

```php
$telegram->restrictChatMember(
    chatId: -1001234567890,
    userId: 987654321,
    permissions: [
        'can_send_messages' => true,
        'can_send_audios' => false,
        'can_send_documents' => false,
        'can_send_photos' => false,
        'can_send_videos' => false,
        'can_send_video_notes' => false,
        'can_send_voice_notes' => false,
        'can_send_polls' => false,
        'can_send_other_messages' => false,
        'can_add_web_page_previews' => false,
    ],
    untilDate: time() + (3600 * 2) // Mute for 2 hours
);
```

---

## 2. Forum Topics Management

In supergroups with Topics enabled, bots can manage forum threads:

### Creating a Topic
```php
use Tueen\Telegram\Enums\ForumIconColor;

$topic = $telegram->createForumTopic(
    chatId: -1001234567890,
    name: '📢 Announcements',
    iconColor: ForumIconColor::BLUE->value, // Or hex RGB color
    iconCustomEmojiId: '5312345678901234567'
);

$threadId = $topic->messageThreadId;

// Send a message directly into this forum topic:
$telegram->sendMessage(
    chatId: -1001234567890,
    messageThreadId: $threadId,
    text: "Welcome to the new Announcements topic!"
);
```

### Closing, Reopening & Deleting Topics
```php
// Close topic (disables posting):
$telegram->closeForumTopic(chatId: -1001234567890, messageThreadId: $threadId);

// Reopen topic:
$telegram->reopenForumTopic(chatId: -1001234567890, messageThreadId: $threadId);

// Delete topic permanently:
$telegram->deleteForumTopic(chatId: -1001234567890, messageThreadId: $threadId);
```

---

## 3. Invite Links & Join Requests

### Creating Trackable Invite Links
```php
$inviteLink = $telegram->createChatInviteLink(
    chatId: -1001234567890,
    name: 'Campaign 2026',
    memberLimit: 100,             // Max 100 users
    expireDate: time() + 86400 * 7, // Expires in 7 days
    createsJoinRequest: true      // Requires admin approval
);

echo "Invite Link: {$inviteLink->inviteLink}\n";
```

### Handling Join Requests (`chat_join_request`)
When `createsJoinRequest` is true, users must be approved by an admin or bot before entering:

```php
use Tueen\Telegram\Enums\UpdateType;
use Tueen\Telegram\Types\Update;

$telegram->run(function (Update $update) use ($telegram) {
    if ($update->type === UpdateType::CHAT_JOIN_REQUEST) {
        $req = $update->chatJoinRequest;

        // Verify criteria (e.g. user answered captcha or paid fee):
        $approved = true;

        if ($approved) {
            $telegram->approveChatJoinRequest(
                chatId: $req->chat->id,
                userId: $req->from->id
            );
        } else {
            $telegram->declineChatJoinRequest(
                chatId: $req->chat->id,
                userId: $req->from->id
            );
        }
    }
});
```
