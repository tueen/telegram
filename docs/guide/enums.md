# Enums Reference

`tueen/telegram` ships with **51 first-class PHP 8.4 Backed Enums** covering every Telegram Bot API string constant. Using native Enums prevents typos, enforces type safety, and gives you rich IDE autocompletion.

---

## 1. Core Telegram Enums

### `ParseMode`
Specifies how Telegram parses entities in messages or captions:
```php
use Tueen\Telegram\Enums\ParseMode;

ParseMode::HTML;        // 'HTML'
ParseMode::MARKDOWN_V2; // 'MarkdownV2'
ParseMode::MARKDOWN;    // 'Markdown' (Legacy)
```
```php
$telegram->sendMessage(
    chatId: 12345,
    text: "<b>Hello</b> <i>World</i>!",
    parseMode: ParseMode::HTML
);
```

### `ChatAction`
Tells the user that something is happening on the bot's side (e.g. typing, uploading media):
```php
use Tueen\Telegram\Enums\ChatAction;

ChatAction::TYPING;              // 'typing'
ChatAction::UPLOAD_PHOTO;        // 'upload_photo'
ChatAction::RECORD_VIDEO;        // 'record_video'
ChatAction::UPLOAD_VIDEO;        // 'upload_video'
ChatAction::RECORD_VOICE;        // 'record_voice'
ChatAction::UPLOAD_VOICE;        // 'upload_voice'
ChatAction::UPLOAD_DOCUMENT;     // 'upload_document'
ChatAction::CHOOSE_STICKER;      // 'choose_sticker'
ChatAction::FIND_LOCATION;       // 'find_location'
ChatAction::RECORD_VIDEO_NOTE;   // 'record_video_note'
ChatAction::UPLOAD_VIDEO_NOTE;   // 'upload_video_note'
```
```php
$telegram->sendChatAction(chatId: 12345, action: ChatAction::TYPING);
```

### `ChatType`
Identifies the type of conversation:
```php
use Tueen\Telegram\Enums\ChatType;

ChatType::SENDER;     // 'sender'
ChatType::PRIVATE;    // 'private'
ChatType::GROUP;      // 'group'
ChatType::SUPERGROUP; // 'supergroup'
ChatType::CHANNEL;    // 'channel'
```
```php
if ($message->chat->type === ChatType::PRIVATE) {
    // 1-on-1 private chat
}
```

### `ChatMemberStatus`
Identifies a user's role and privileges in a chat or channel:
```php
use Tueen\Telegram\Enums\ChatMemberStatus;

ChatMemberStatus::CREATOR;       // 'creator'
ChatMemberStatus::ADMINISTRATOR; // 'administrator'
ChatMemberStatus::MEMBER;        // 'member'
ChatMemberStatus::RESTRICTED;    // 'restricted'
ChatMemberStatus::LEFT;          // 'left'
ChatMemberStatus::KICKED;        // 'kicked'
```

### `DiceEmoji`
Animation types for `sendDice`:
```php
use Tueen\Telegram\Enums\DiceEmoji;

DiceEmoji::DICE;         // '🎲' (Values 1-6)
DiceEmoji::DARTS;        // '🎯' (Values 1-6)
DiceEmoji::BASKETBALL;   // '🏀' (Values 1-5)
DiceEmoji::FOOTBALL;     // '⚽' (Values 1-5)
DiceEmoji::SLOT_MACHINE; // '🎰' (Values 1-64)
DiceEmoji::BOWLING;      // '🎳' (Values 1-6)
```
```php
$res = $telegram->sendDice(chatId: 12345, emoji: DiceEmoji::SLOT_MACHINE);
echo "Result value: {$res->dice->value}\n";
```

### `ButtonStyle`
Telegram Bot API 10.3 button colors for inline keyboards:
```php
use Tueen\Telegram\Enums\ButtonStyle;

ButtonStyle::PRIMARY; // 'primary' (blue)
ButtonStyle::SUCCESS; // 'success' (green)
ButtonStyle::DANGER;  // 'danger' (red)
```

### `BotCommandScopeType`
Defines who can see specific bot commands:
```php
use Tueen\Telegram\Enums\BotCommandScopeType;

BotCommandScopeType::DEFAULT;                  // 'default'
BotCommandScopeType::ALL_PRIVATE_CHATS;        // 'all_private_chats'
BotCommandScopeType::ALL_GROUP_CHATS;          // 'all_group_chats'
BotCommandScopeType::ALL_CHAT_ADMINISTRATORS;  // 'all_chat_administrators'
BotCommandScopeType::CHAT;                     // 'chat'
BotCommandScopeType::CHAT_ADMINISTRATORS;      // 'chat_administrators'
BotCommandScopeType::CHAT_MEMBER;              // 'chat_member'
```

### `PollType`
```php
use Tueen\Telegram\Enums\PollType;

PollType::REGULAR; // 'regular'
PollType::QUIZ;    // 'quiz'
```

---

## 2. Update & Message Enums

### `UpdateType` (34 Update Types)
Every update received from Telegram is classified into an `UpdateType` enum:

| Enum Case | Value | Trigger |
| :--- | :--- | :--- |
| `UpdateType::MESSAGE` | `'message'` | Incoming user message |
| `UpdateType::EDITED_MESSAGE` | `'edited_message'` | Edited message |
| `UpdateType::CHANNEL_POST` | `'channel_post'` | New channel post |
| `UpdateType::EDITED_CHANNEL_POST` | `'edited_channel_post'` | Edited channel post |
| `UpdateType::BUSINESS_CONNECTION` | `'business_connection'` | Telegram Business connected/disconnected |
| `UpdateType::BUSINESS_MESSAGE` | `'business_message'` | Message in Telegram Business chat |
| `UpdateType::EDITED_BUSINESS_MESSAGE` | `'edited_business_message'` | Edited Business message |
| `UpdateType::DELETED_BUSINESS_MESSAGES` | `'deleted_business_messages'` | Deleted Business messages |
| `UpdateType::MESSAGE_REACTION` | `'message_reaction'` | User reacted to a message |
| `UpdateType::MESSAGE_REACTION_COUNT` | `'message_reaction_count'` | Anonymous reaction tally changed |
| `UpdateType::INLINE_QUERY` | `'inline_query'` | Inline query typed (`@bot query`) |
| `UpdateType::CHOSEN_INLINE_RESULT` | `'chosen_inline_result'` | Inline result clicked |
| `UpdateType::CALLBACK_QUERY` | `'callback_query'` | Inline keyboard button tapped |
| `UpdateType::SHIPPING_QUERY` | `'shipping_query'` | Shipping query for invoice |
| `UpdateType::PRE_CHECKOUT_QUERY` | `'pre_checkout_query'` | Final order verification before payment |
| `UpdateType::PURCHASED_PAID_MEDIA` | `'purchased_paid_media'` | Telegram Stars paid media purchased |
| `UpdateType::POLL` | `'poll'` | Poll state change |
| `UpdateType::POLL_ANSWER` | `'poll_answer'` | User voted in a non-anonymous poll |
| `UpdateType::MY_CHAT_MEMBER` | `'my_chat_member'` | Bot's status in a chat updated |
| `UpdateType::CHAT_MEMBER` | `'chat_member'` | A member's status updated in a chat |
| `UpdateType::CHAT_JOIN_REQUEST` | `'chat_join_request'` | User requested to join a private group/channel |
| `UpdateType::CHAT_BOOST` | `'chat_boost'` | Premium user boosted the chat |
| `UpdateType::REMOVED_CHAT_BOOST` | `'removed_chat_boost'` | Boost expired or removed |

---

### `MessageType` (53 Message Types)
Classifies the content of a `Message` object:

| Category | Enum Cases |
| :--- | :--- |
| **Text & Rich Text** | `TEXT`, `RICH_MESSAGE` |
| **Media Attachments** | `PHOTO`, `VIDEO`, `AUDIO`, `VOICE`, `DOCUMENT`, `STICKER`, `ANIMATION`, `VIDEO_NOTE`, `PAID_MEDIA` |
| **Interactive & Games** | `DICE`, `GAME`, `POLL`, `LOCATION`, `VENUE`, `CONTACT`, `STORY` |
| **System & Chat Events** | `NEW_CHAT_MEMBERS`, `LEFT_CHAT_MEMBER`, `NEW_CHAT_TITLE`, `NEW_CHAT_PHOTO`, `DELETE_CHAT_PHOTO`, `GROUP_CHAT_CREATED`, `SUPERGROUP_CHAT_CREATED`, `CHANNEL_CHAT_CREATED`, `PINNED_MESSAGE` |
| **Payments & Stars** | `INVOICE`, `SUCCESSFUL_PAYMENT`, `REFUNDED_PAYMENT` |
| **Forum & Topics** | `FORUM_TOPIC_CREATED`, `FORUM_TOPIC_EDITED`, `FORUM_TOPIC_CLOSED`, `FORUM_TOPIC_REOPENED`, `GENERAL_FORUM_TOPIC_HIDDEN`, `GENERAL_FORUM_TOPIC_UNHIDDEN` |
| **Giveaways & Boosts** | `GIVEAWAY`, `GIVEAWAY_CREATED`, `GIVEAWAY_WINNERS`, `GIVEAWAY_COMPLETED`, `BOOST_ADDED` |
| **Web Apps & Passport** | `CONNECTED_WEBSITE`, `WRITE_ACCESS_ALLOWED`, `PASSPORT_DATA`, `USERS_SHARED`, `CHAT_SHARED` |

---

## 3. All 51 Library Enums

For reference, here is the complete index of Enums available in `Tueen\Telegram\Enums`:

- `BackgroundFillType`
- `BackgroundTypeType`
- `BotCommandScopeType`
- `ButtonStyle`
- `ChatAction`
- `ChatBoostSourceSource`
- `ChatJoinRequestResult`
- `ChatMemberStatus`
- `ChatType`
- `Currency`
- `DiceEmoji`
- `ErrorHandlingMode`
- `ForumIconColor`
- `InlineQueryResultType`
- `InputMediaType`
- `InputPaidMediaType`
- `InputProfilePhotoType`
- `InputRichBlockButtonsAlign`
- `InputRichBlockType`
- `InputStoryContentType`
- `MaskPositionPoint`
- `MenuButtonType`
- `MessageEntityType`
- `MessageOriginType`
- `MessageType`
- `OwnedGiftType`
- `PaidMediaType`
- `ParseMode`
- `PassportSource`
- `PassportType`
- `PollType`
- `ReactionTypeType`
- `RevenueWithdrawalStateType`
- `RichBlockButtonsAlign`
- `RichBlockListItemType`
- `RichBlockTableCellAlign`
- `RichBlockTableCellValign`
- `RichBlockType`
- `RichTextType`
- `StickerFormat`
- `StickerType`
- `StoryActivePeriod`
- `StoryAreaTypeType`
- `SubscriptionState`
- `SuggestedPostInfoState`
- `SuggestedPostRefundedReason`
- `TransactionPartnerType`
- `UniqueGiftInfoOrigin`
- `UniqueGiftModelRarity`
- `UpdateType`
- `VideoQualityCodec`
