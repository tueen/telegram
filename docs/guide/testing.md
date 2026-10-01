# Testing & Fakes

Testing Telegram bots often poses challenges: calling real Telegram API servers during automated tests is slow, rate-limited, and may inadvertently message real users.

`tueen/telegram` ships with built-in testing fakes inspired by modern testing paradigms. With `Telegram::fake()`, you can intercept all outbound API calls, stub custom responses, and assert that expected API methods were called with precise parameters.

---

## 🧪 1. Quick Example

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

        $bot->router->dispatch($update, $bot);

        // 4. Assert that sendMessage was called with expected parameters
        $bot->assertSent('sendMessage', function (array $params) {
            return $params['chat_id'] === 12345 && $params['text'] === 'Welcome!';
        });

        $bot->assertSentCount('sendMessage', 1);
    }
}
```

---

## 🛠️ 2. Response Stubbing

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

## 🔍 3. Inspecting Sent Requests

You can inspect the recorded requests directly:

```php
$requests = $bot->sentRequests(); // list of recorded Request objects
$lastRequest = $bot->lastSentRequest();

echo $lastRequest->method; // e.g. "sendMessage"
print_r($lastRequest->params);
```

---

## 🧭 4. Testing & Fakes API Catalog

Below is the complete reference of assertion methods, stubbing helpers, and inspection APIs available on `Tueen\Telegram\Testing\TelegramFake`.

### 🛡️ Assertion Methods (`TelegramFake`)

<ApiGroup description="In-memory test assertions for verifying outbound Telegram API method invocations.">
  <ApiCard
    sig="assertSent(string $method, ?callable $callback = null): void"
    returns="void"
    badge="Assertion"
    desc="Asserts that a specific Telegram API method was invoked at least once, optionally matching callback predicate."
  />
  <ApiCard
    sig="assertNotSent(string $method, ?callable $callback = null): void"
    returns="void"
    badge="Assertion"
    desc="Asserts that a specific Telegram API method was never invoked, or never matched the callback predicate."
  />
  <ApiCard
    sig="assertSentCount(string $method, int $expectedCount): void"
    returns="void"
    badge="Assertion"
    desc="Asserts that a specific Telegram API method was called exactly the expected number of times."
  />
  <ApiCard
    sig="assertNothingSent(): void"
    returns="void"
    badge="Assertion"
    desc="Asserts that zero outbound API requests were dispatched to Telegram during test execution."
  />
</ApiGroup>

---

### 🎭 Stubbing & Inspection Methods (`TelegramFake`)

<ApiGroup description="Methods for mocking API responses and inspecting recorded request objects.">
  <ApiCard
    sig="Telegram::fake(array $responses = [], string $botToken = 'FAKE_BOT_TOKEN'): TelegramFake"
    returns="TelegramFake"
    badge="Factory"
    desc="Creates an in-memory recording fake client pre-seeded with optional response stubs."
  />
  <ApiCard
    sig="fakeResponse(string $method, array $response): static"
    returns="static"
    badge="Stubbing"
    desc="Registers a mock successful response array to be returned when the given method is called."
  />
  <ApiCard
    sig="fakeError(string $method, string $description, int $errorCode = 400): static"
    returns="static"
    badge="Stubbing"
    desc="Configures the fake to simulate a Telegram API error with HTTP status code and error description."
  />
  <ApiCard
    sig="sentRequests(): array<int, Request>"
    returns="array<int, Request>"
    badge="Inspection"
    desc="Returns all recorded HTTP Request instances dispatched during the test lifecycle."
  />
  <ApiCard
    sig="lastSentRequest(): ?Request"
    returns="?Request"
    badge="Inspection"
    desc="Returns the most recently recorded Request instance, or null if no calls were made."
  />
</ApiGroup>
