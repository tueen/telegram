# Commands & Bot Profile Settings

Telegram allows bots to programmatically manage their `/commands` menu, profile descriptions, and chat menu buttons without manual intervention in @BotFather.

---

## 1. Setting Bot Commands (`setMyCommands`)

The `/commands` menu allows users to see available commands with a tap on the `[/]` button in the input field.

### Basic Command Registration

```php
use Tueen\Telegram\Telegram;

$telegram = new Telegram('YOUR_BOT_TOKEN');

$telegram->setMyCommands(
    commands: [
        ['command' => 'start', 'description' => 'Launch the bot and show welcome screen'],
        ['command' => 'help', 'description' => 'Show help information and usage guide'],
        ['command' => 'settings', 'description' => 'Configure notifications and language'],
    ]
);
```

### Scoped Commands (`BotCommandScope`)

You can define different command sets for different audiences using `scope`:

```php
use Tueen\Telegram\Types\BotCommandScopeAllGroupChats;
use Tueen\Telegram\Types\BotCommandScopeAllChatAdministrators;

// Commands visible only in group chats:
$telegram->setMyCommands(
    commands: [
        ['command' => 'report', 'description' => 'Report a message to group admins'],
        ['command' => 'rules', 'description' => 'Read group rules'],
    ],
    scope: new BotCommandScopeAllGroupChats()
);

// Commands visible only to chat administrators:
$telegram->setMyCommands(
    commands: [
        ['command' => 'ban', 'description' => 'Ban a spammer from the group'],
        ['command' => 'mute', 'description' => 'Temporarily restrict a user'],
        ['command' => 'stats', 'description' => 'View moderation statistics'],
    ],
    scope: new BotCommandScopeAllChatAdministrators()
);
```

### Multilingual Commands

Set localized command descriptions for specific user languages using two-letter ISO 639-1 codes:

```php
// Persian commands:
$telegram->setMyCommands(
    commands: [
        ['command' => 'start', 'description' => 'شروع کار با ربات'],
        ['command' => 'help', 'description' => 'راهنما و پشتیبانی'],
    ],
    languageCode: 'fa'
);
```

---

## 2. Managing Bot Profile Information

Update the bot's name, description, and "About" text directly via code:

```php
// 1. Set bot display name:
$telegram->setMyName(name: '👑 Tueen Assistant');

// 2. Set full description (shown on empty chat screen before clicking "Start"):
$telegram->setMyDescription(
    description: "Welcome to Tueen Assistant!\n\nThe official bot powered by tueen/telegram."
);

// 3. Set short description (shown in bot profile and share links):
$telegram->setMyShortDescription(
    shortDescription: 'The Royal Telegram Bot Client assistant.'
);
```

---

## 3. Configuring the Chat Menu Button (`setChatMenuButton`)

Change the button displayed next to the message input field:

```php
// Option A: Restore default commands menu button
$telegram->setChatMenuButton(
    menuButton: ['type' => 'commands']
);

// Option B: Launch a Telegram Web App / Mini App directly from the input bar!
$telegram->setChatMenuButton(
    menuButton: [
        'type' => 'web_app',
        'text' => '🚀 Launch App',
        'web_app' => ['url' => 'https://tueen.dev/mini-app']
    ]
);
```
