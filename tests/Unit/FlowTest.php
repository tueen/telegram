<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Flow\Flow;
use Tueen\Telegram\Flow\FlowManager;
use Tueen\Telegram\Flow\FlowState;
use Tueen\Telegram\Flow\Storage\FileStateStore;
use Tueen\Telegram\Flow\Storage\MemoryStateStore;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

// Mock Flows for testing
class TestRegistrationFlow extends Flow
{
    public function start(Update $update): void
    {
        $this->bot->sendMessage(chatId: $this->chatId, text: 'Please enter your name:');
        $this->to('askEmail');
    }

    public function askEmail(Update $update): void
    {
        $name = trim($update->findAnyText() ?? '');

        if ($name === 'invalid') {
            $this->stay('Invalid name, try again:');
            return;
        }

        $this->set('name', $name);
        $this->bot->sendMessage(chatId: $this->chatId, text: "Thanks, {$name}! What is your email?");
        $this->to('confirm');
    }

    public function confirm(Update $update): void
    {
        $email = trim($update->findAnyText() ?? '');

        if ($email === 'back') {
            $this->back('Going back to name step...');
            return;
        }

        if ($email === 'jump') {
            $this->jumpTo(TestFeedbackFlow::class, initialData: ['referrer' => 'registration']);
            return;
        }

        $this->set('email', $email);
        $name = $this->get('name');

        $this->bot->sendMessage(chatId: $this->chatId, text: "All done for {$name} with {$email}!");
        $this->finish();
    }
}

class TestFeedbackFlow extends Flow
{
    public function start(Update $update): void
    {
        $ref = $this->get('referrer', 'direct');
        $this->bot->sendMessage(chatId: $this->chatId, text: "Feedback form (ref: {$ref})");
        $this->to('receiveComment');
    }

    public function receiveComment(Update $update): void
    {
        $comment = $update->findAnyText();
        $this->bot->sendMessage(chatId: $this->chatId, text: "Comment received: {$comment}");
        $this->finish();
    }
}

class FlowTest extends TestCase
{
    private function createUpdate(string $text, int $chatId = 12345, int $userId = 12345, int $updateId = 1): Update
    {
        return new Update([
            'update_id' => $updateId,
            'message' => [
                'message_id' => $updateId * 10,
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Tester'],
                'text' => $text,
            ],
        ]);
    }

    public function testCompleteFlowCycle(): void
    {
        $bot = Telegram::fake();

        // 1. Initial trigger: start the flow
        $startUpdate = $this->createUpdate('/register', 12345, 12345, 1);
        $bot->startFlow(TestRegistrationFlow::class, $startUpdate);

        $bot->assertSent('sendMessage', fn(array $params) => $params['text'] === 'Please enter your name:');
        $this->assertTrue($bot->flowManager()->hasActiveFlow(12345));

        // 2. Step 2: send name
        $nameUpdate = $this->createUpdate('Alice', 12345, 12345, 2);
        $handled = $bot->flowManager()->handle($nameUpdate, $bot);

        $this->assertTrue($handled);
        $bot->assertSent('sendMessage', fn(array $params) => str_contains($params['text'], 'Thanks, Alice!'));

        // Check active state
        $state = $bot->flowManager()->getActiveState(12345);
        $this->assertNotNull($state);
        $this->assertSame('confirm', $state->currentStep);
        $this->assertSame('Alice', $state->data['name']);

        // 3. Step 3: send email to finish
        $emailUpdate = $this->createUpdate('alice@example.com', 12345, 12345, 3);
        $handled = $bot->flowManager()->handle($emailUpdate, $bot);

        $this->assertTrue($handled);
        $bot->assertSent('sendMessage', fn(array $params) => str_contains($params['text'], 'All done for Alice with alice@example.com!'));

        // Verify flow is finished and deleted
        $this->assertFalse($bot->flowManager()->hasActiveFlow(12345));
    }

    public function testFlowStayOnValidationFailure(): void
    {
        $bot = Telegram::fake();

        $startUpdate = $this->createUpdate('/register');
        $bot->startFlow(TestRegistrationFlow::class, $startUpdate);

        // Send invalid name
        $invalidUpdate = $this->createUpdate('invalid');
        $bot->flowManager()->handle($invalidUpdate, $bot);

        $bot->assertSent('sendMessage', fn(array $params) => $params['text'] === 'Invalid name, try again:');

        // Still on askEmail step
        $state = $bot->flowManager()->getActiveState(12345);
        $this->assertSame('askEmail', $state->currentStep);
    }

    public function testFlowBackNavigation(): void
    {
        $bot = Telegram::fake();

        $bot->startFlow(TestRegistrationFlow::class, $this->createUpdate('/register'));
        $bot->flowManager()->handle($this->createUpdate('Bob'), $bot);

        $state = $bot->flowManager()->getActiveState(12345);
        $this->assertSame('confirm', $state->currentStep);

        // Send 'back' keyword
        $bot->flowManager()->handle($this->createUpdate('back'), $bot);

        $bot->assertSent('sendMessage', fn(array $params) => $params['text'] === 'Going back to name step...');

        $state = $bot->flowManager()->getActiveState(12345);
        $this->assertSame('askEmail', $state->currentStep);
    }

    public function testFlowJumpToAnotherFlow(): void
    {
        $bot = Telegram::fake();

        $bot->startFlow(TestRegistrationFlow::class, $this->createUpdate('/register'));
        $bot->flowManager()->handle($this->createUpdate('Charlie'), $bot);

        // In confirm step, send 'jump' to switch to FeedbackFlow
        $bot->flowManager()->handle($this->createUpdate('jump'), $bot);

        $bot->assertSent('sendMessage', fn(array $params) => str_contains($params['text'], 'Feedback form (ref: registration)'));

        $state = $bot->flowManager()->getActiveState(12345);
        $this->assertSame(TestFeedbackFlow::class, $state->flowClass);
        $this->assertSame('receiveComment', $state->currentStep);
    }

    public function testFlowExitCommands(): void
    {
        $bot = Telegram::fake();

        $bot->startFlow(TestRegistrationFlow::class, $this->createUpdate('/register'));
        $this->assertTrue($bot->flowManager()->hasActiveFlow(12345));

        // Send /cancel
        $handled = $bot->flowManager()->handle($this->createUpdate('/cancel@my_bot'), $bot);

        $this->assertTrue($handled);
        $bot->assertSent('sendMessage', fn(array $params) => $params['text'] === 'Operation cancelled.');
        $this->assertFalse($bot->flowManager()->hasActiveFlow(12345));
    }

    public function testFlowIntegrationWithRouter(): void
    {
        $bot = Telegram::fake();

        $bot->onCommand('register', function (Update $update, Telegram $bot) {
            $bot->startFlow(TestRegistrationFlow::class, $update);
        });

        $bot->onMessage('/hello/', function (Update $update, Telegram $bot) {
            $bot->sendMessage(chatId: $update->findChat()->id, text: 'Hello World!');
        });

        // 1. Send /register -> starts flow
        $bot->router()->dispatch($this->createUpdate('/register'), $bot);
        $bot->assertSent('sendMessage', fn(array $params) => $params['text'] === 'Please enter your name:');

        // 2. Send 'hello' while in flow -> should be intercepted by Flow, NOT by the /hello/ route!
        $bot->router()->dispatch($this->createUpdate('hello'), $bot);
        $bot->assertSent('sendMessage', fn(array $params) => str_contains($params['text'], 'Thanks, hello!'));
        $bot->assertNotSent('sendMessage', fn(array $params) => $params['text'] === 'Hello World!');
    }

    public function testFileStateStore(): void
    {
        $tmpDir = sys_get_temp_dir() . '/tueen_test_flows_' . uniqid();
        $store = new FileStateStore($tmpDir);

        $state = new FlowState(
            flowClass: TestRegistrationFlow::class,
            currentStep: 'askEmail',
            data: ['role' => 'admin']
        );

        $store->set('test_session', $state);

        $loaded = $store->get('test_session');
        $this->assertNotNull($loaded);
        $this->assertSame(TestRegistrationFlow::class, $loaded->flowClass);
        $this->assertSame('askEmail', $loaded->currentStep);
        $this->assertSame('admin', $loaded->data['role']);

        $store->delete('test_session');
        $this->assertNull($store->get('test_session'));

        $store->clear();
        @rmdir($tmpDir);
    }

    public function testRedisStateStore(): void
    {
        $mockRedis = new class {
            public array $data = [];
            public function get(string $key): ?string { return $this->data[$key] ?? null; }
            public function set(string $key, string $val): void { $this->data[$key] = $val; }
            public function setex(string $key, int $ttl, string $val): void { $this->data[$key] = $val; }
            public function del(string $key): void { unset($this->data[$key]); }
            public function keys(string $pattern): array {
                $prefix = rtrim($pattern, '*');
                return array_values(array_filter(array_keys($this->data), fn($k) => str_starts_with($k, $prefix)));
            }
        };

        $store = new \Tueen\Telegram\Flow\Storage\RedisStateStore($mockRedis, 'test:flow:');

        $state = new FlowState(
            flowClass: TestRegistrationFlow::class,
            currentStep: 'askEmail',
            data: ['redis' => true]
        );

        $store->set('user_123', $state, 3600);
        $loaded = $store->get('user_123');
        $this->assertNotNull($loaded);
        $this->assertSame(TestRegistrationFlow::class, $loaded->flowClass);
        $this->assertTrue($loaded->data['redis']);

        $store->delete('user_123');
        $this->assertNull($store->get('user_123'));
    }

    public function testPsr16StateStore(): void
    {
        $mockCache = new class {
            public array $data = [];
            public function get(string $key): mixed { return $this->data[$key] ?? null; }
            public function set(string $key, mixed $val, ?int $ttl = null): bool { $this->data[$key] = $val; return true; }
            public function delete(string $key): bool { unset($this->data[$key]); return true; }
            public function clear(): bool { $this->data = []; return true; }
        };

        $store = new \Tueen\Telegram\Flow\Storage\Psr16StateStore($mockCache, 'flow:');

        $state = new FlowState(
            flowClass: TestRegistrationFlow::class,
            currentStep: 'confirm',
            data: ['score' => 99]
        );

        $store->set('session_456', $state);
        $loaded = $store->get('session_456');
        $this->assertNotNull($loaded);
        $this->assertSame(99, $loaded->data['score']);

        $store->delete('session_456');
        $this->assertNull($store->get('session_456'));
    }
}
