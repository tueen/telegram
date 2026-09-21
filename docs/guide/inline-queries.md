# Inline Queries & Inline Mode

Telegram's **Inline Mode** allows users to invoke your bot from *any* chat, group, or channel by typing `@yourbot query` into the text input field.

When the user types a query, Telegram sends an `inline_query` update to your bot, which answers with up to 50 results (articles, photos, videos, stickers, etc.) that the user can immediately select and share.

---

## 1. Enabling Inline Mode

To enable inline mode for your bot:
1. Open [@BotFather](https://t.me/BotFather) on Telegram.
2. Send `/setinline`.
3. Select your bot and provide a placeholder text (e.g., `Search music...` or `Search articles...`).

---

## 2. Handling `inline_query` Updates

When a user triggers an inline query, the `Update` contains an `inline_query` object:

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Enums\UpdateType;

$telegram = new Telegram('YOUR_BOT_TOKEN');

$telegram->run(function (Update $update) use ($telegram) {
    if ($update->type !== UpdateType::INLINE_QUERY) {
        return;
    }

    $query = $update->inlineQuery;
    $searchTerm = trim($query->query);

    // Build array of results (up to 50 items)
    $results = [];

    if (empty($searchTerm)) {
        // Default suggestions
        $results[] = [
            'type' => 'article',
            'id' => '1',
            'title' => '👑 Tueen Telegram',
            'description' => 'The Royal Telegram Bot API Client for PHP 8.4',
            'input_message_content' => [
                'message_text' => "Check out **Tueen Telegram**:\nhttps://github.com/tueen/telegram",
                'parse_mode' => 'Markdown'
            ]
        ];
    } else {
        // Search matching results
        $results[] = [
            'type' => 'article',
            'id' => md5($searchTerm),
            'title' => "Result for: {$searchTerm}",
            'description' => "Click to send details about {$searchTerm}",
            'input_message_content' => [
                'message_text' => "You searched for: **{$searchTerm}**",
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    // Answer the inline query:
    $telegram->answerInlineQuery(
        inlineQueryId: $query->id,
        results: $results,
        cacheTime: 300,   // Cache for 5 minutes
        isPersonal: false // Results can be cached across users
    );
});
```

---

## 3. Supported Inline Result Types

Telegram supports multiple formats via the `type` field:

| Type | Description |
| :--- | :--- |
| `'article'` | Link to an article or text snippet. Requires `input_message_content`. |
| `'photo'` | Link to a photo (JPEG). Displays thumbnail and caption. |
| `'gif'` / `'mpeg4_gif'` | Animated GIF or H.264 video. |
| `'video'` | Embedded video clip. |
| `'audio'` | MP3 audio track. |
| `'voice'` | OGG voice recording. |
| `'document'` | PDF or other downloadable file. |
| `'location'` / `'venue'` | Map location or venue with address. |

---

## 4. Switch to Private Chat Button (`button`)

You can display a special button above the inline results to guide users to a private chat with the bot:

```php
$telegram->answerInlineQuery(
    inlineQueryId: $query->id,
    results: $results,
    button: [
        'text' => '⚙️ Configure Bot Settings',
        'start_parameter' => 'settings' // Deep link passed to /start
    ]
);
```

---

## 5. Tracking Selected Results (`chosen_inline_result`)

If enabled via `/setinlinefeedback` in @BotFather, Telegram sends a `chosen_inline_result` update whenever a user selects one of your inline results:

```php
if ($update->type === UpdateType::CHOSEN_INLINE_RESULT) {
    $chosen = $update->chosenInlineResult;
    // Log analytics:
    error_log("User {$chosen->from->id} selected result ID: {$chosen->resultId}");
}
```
