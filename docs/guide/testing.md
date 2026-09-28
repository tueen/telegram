# Testing & Fakes

Testing Telegram bots often poses challenges: calling real Telegram API servers during automated tests is slow, rate-limited, and may inadvertently message real users.

`tueen/telegram` ships with built-in testing fakes inspired by modern testing paradigms. With `Telegram::fake()`, you can intercept all outbound API calls, stub custom responses, and assert that expected API methods were called with precise parameters.

---

## 1. Quick Example

```php
use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class WelcomeBotTest extends TestCase
{
    public function test_it_sends_welcome_message_on_start(): void
    {
        // 1. Swap real client with in-memory recording fake
        $bot = Telegram::fake();

        // 2. Simulate an incoming update
        $update = new Update([
            'update_id' => 1,
            'message' => [
                'message_id' => 10,
                'chat' => ['id' => 12345, 'type' => 'private'],
                'text' => '/start',
            ],
        ]);

        // 3. Run handler or controller
        $bot->onCommand('start', function (Update $update, Telegram $bot) {
            $bot->sendMessage(
                chatId: $update->findChat()->id,
                text: 'Welcome!'
            );
        });

        $bot->router()->dispatch($update, $bot);

        // 4. Assert that sendMessage was called with expected parameters
        $bot->assertSent('sendMessage', function (array $params) {
            return $params['chat_id'] === 12345 && $params['text'] === 'Welcome!';
        });

        $bot->assertSentCount('sendMessage', 1);
    }
}
```

---

## 2. Available Assertion Methods

All assertions can be called directly on the `$bot` fake instance:

### `assertSent(string $method, ?callable $callback = null)`
Asserts that an API method was invoked at least once. If a callback is provided, it receives the `$params` array and must return `true`.

```php
$bot->assertSent('sendMessage');

$bot->assertSent('sendMessage', fn(array $params) => $params['text'] === 'Hello!');
```

### `assertNotSent(string $method, ?callable $callback = null)`
Asserts that an API method was never called, or was never called with matching parameters.

```php
$bot->assertNotSent('deleteMessage');
```

### `assertSentCount(string $method, int $expectedCount)`
Asserts that an API method was invoked exactly `$expectedCount` times.

```php
$bot->assertSentCount('sendMessage', 2);
```

### `assertNothingSent()`
Asserts that zero API calls were made to Telegram during the test.

```php
$bot->assertNothingSent();
```

---

## 3. Response Stubbing

By default, `Telegram::fake()` provides a generic success response (`['ok' => true, 'result' => []]`) for any method called.

You can customize response stubs to test how your application handles specific Telegram responses or errors:

### Stubbing Successful Responses

```php
$bot = Telegram::fake();

// Stub a specific method response:
$bot->fakeResponse('getMe', [
    'ok' => true,
    'result' => [
        'id' => 999999,
        'is_bot' => true,
        'first_name' => 'TestBot',
        'username' => 'test_bot',
    ],
]);

$user = $bot->getMe();
$this->assertSame('test_bot', $user->username);
```

### Stubbing Telegram API Errors

Test how your system responds when Telegram returns an error (e.g. user blocked the bot):

```php
$bot = Telegram::fake();

$bot->fakeError('sendMessage', 'Forbidden: bot was blocked by the user', 403);

// In ErrorHandlingMode::EXCEPTION:
$this->expectException(\Tueen\Telegram\Exceptions\TelegramException::class);
$bot->sendMessage(chatId: 123, text: 'Hello');
```

---

## 4. Inspecting Sent Requests

You can inspect the recorded requests directly:

```php
$requests = $bot->sentRequests(); // list of recorded Request objects
$lastRequest = $bot->lastSentRequest();

echo $lastRequest->method; // e.g. "sendMessage"
print_r($lastRequest->params);
```
