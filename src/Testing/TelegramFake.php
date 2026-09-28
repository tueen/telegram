<?php

declare(strict_types=1);

namespace Tueen\Telegram\Testing;

use Closure;
use PHPUnit\Framework\Assert;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Running\WebhookMode;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

/**
 * High-level testing fake for tueen/telegram with fluent assertions.
 *
 * Example:
 * $fake = Telegram::fake(['getMe' => ['username' => 'TestBot']]);
 * $fake->sendMessage(chatId: 123, text: 'Hello');
 * $fake->assertSent('sendMessage', fn(Request $r) => $r->parameters['chat_id'] === 123);
 */
class TelegramFake extends Telegram
{
    private FakeHttpClient $fakeClient;

    public function __construct(
        string|Config $tokenOrConfig = 'FAKE_BOT_TOKEN',
        array $initialResponses = []
    ) {
        $this->fakeClient = new FakeHttpClient($initialResponses);

        if (is_string($tokenOrConfig)) {
            $config = Telegram::create($tokenOrConfig)
                ->withHttpClient($this->fakeClient)
                ->build();
        } else {
            $config = $tokenOrConfig->withHttpClient($this->fakeClient);
        }

        parent::__construct($config);
    }

    public function getFakeClient(): FakeHttpClient
    {
        return $this->fakeClient;
    }

    /**
     * Stubs a response for a specific API method endpoint.
     */
    public function fakeResponse(string $endpoint, mixed $response): static
    {
        $this->fakeClient->fakeResponse($endpoint, $response);
        return $this;
    }

    /**
     * Simulates receiving an incoming update and runs it through registered handlers.
     */
    public function fakeUpdate(array|Update $update): mixed
    {
        $updateInstance = $update instanceof Update ? $update : new Update($update);
        $this->setUpdate($updateInstance);
        $payload = json_encode($updateInstance->toArray());

        $this->setRunningMode(new WebhookMode(rawInput: $payload));
        return $this->run();
    }

    /**
     * Asserts that a specific method or endpoint was called.
     *
     * @param class-string<Method>|string|callable(Request): bool $methodOrEndpoint
     * @param (callable(Request): bool)|null $callback
     */
    public function assertSent(string|callable $methodOrEndpoint, ?callable $callback = null): void
    {
        $endpoint = null;

        if (is_string($methodOrEndpoint)) {
            if (is_subclass_of($methodOrEndpoint, Method::class)) {
                $endpoint = lcfirst(basename(str_replace('\\', '/', $methodOrEndpoint)));
            } else {
                $endpoint = ltrim($methodOrEndpoint, '/');
            }
        } elseif (is_callable($methodOrEndpoint) && $callback === null) {
            $callback = $methodOrEndpoint;
        }

        $recorded = $this->fakeClient->recorded($endpoint);

        if (empty($recorded)) {
            $msg = "Failed asserting that [{$endpoint}] was sent.";
            $this->failAssertion($msg);
            return;
        }

        if ($callback !== null) {
            $matched = false;
            foreach ($recorded as $entry) {
                if ($this->invokeCallback($callback, $entry['request'])) {
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $msg = "Failed asserting that [{$endpoint}] matched the given truth callback.";
                $this->failAssertion($msg);
                return;
            }
        }

        $this->passAssertion();
    }

    /**
     * Asserts that a specific method or endpoint was never called.
     *
     * @param class-string<Method>|string|callable(Request): bool $methodOrEndpoint
     * @param (callable(Request): bool)|null $callback
     */
    public function assertNotSent(string|callable $methodOrEndpoint, ?callable $callback = null): void
    {
        $endpoint = null;

        if (is_string($methodOrEndpoint)) {
            if (is_subclass_of($methodOrEndpoint, Method::class)) {
                $endpoint = lcfirst(basename(str_replace('\\', '/', $methodOrEndpoint)));
            } else {
                $endpoint = ltrim($methodOrEndpoint, '/');
            }
        } elseif (is_callable($methodOrEndpoint) && $callback === null) {
            $callback = $methodOrEndpoint;
        }

        $recorded = $this->fakeClient->recorded($endpoint);

        if ($callback === null) {
            if (!empty($recorded)) {
                $this->failAssertion("Expected [{$endpoint}] not to be sent, but it was sent " . count($recorded) . " times.");
                return;
            }
        } else {
            foreach ($recorded as $entry) {
                if ($this->invokeCallback($callback, $entry['request'])) {
                    $this->failAssertion("Expected [{$endpoint}] not to match the given truth callback, but it did.");
                    return;
                }
            }
        }

        $this->passAssertion();
    }

    private function invokeCallback(callable $callback, Request $request): bool
    {
        $reflection = new \ReflectionFunction(\Closure::fromCallable($callback));
        $firstParam = $reflection->getParameters()[0] ?? null;
        $type = $firstParam?->getType();

        if ($type instanceof \ReflectionNamedType && $type->getName() === 'array') {
            return (bool) $callback($request->params, $request);
        }

        return (bool) $callback($request, $request->params);
    }

    /**
     * Asserts that the total number of requests sent matches the expected count.
     */
    public function assertSentCount(int $expectedCount, ?string $endpoint = null): void
    {
        $actual = count($this->fakeClient->recorded($endpoint));

        if ($actual !== $expectedCount) {
            $target = $endpoint !== null ? "for [{$endpoint}]" : "in total";
            $this->failAssertion("Expected {$expectedCount} requests {$target}, but {$actual} were sent.");
            return;
        }

        $this->passAssertion();
    }

    /**
     * Asserts that no requests were sent at all.
     */
    public function assertNothingSent(): void
    {
        $this->assertSentCount(0);
    }

    private function failAssertion(string $message): void
    {
        if (class_exists(Assert::class)) {
            Assert::fail($message);
        } else {
            throw new TelegramException($message);
        }
    }

    private function passAssertion(): void
    {
        if (class_exists(Assert::class)) {
            Assert::assertTrue(true);
        }
    }
}
