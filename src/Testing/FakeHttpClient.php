<?php

declare(strict_types=1);

namespace Tueen\Telegram\Testing;

use Closure;
use Tueen\Telegram\Client\HttpClientInterface;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;

/**
 * In-memory HTTP client fake for recording requests and simulating API responses.
 */
class FakeHttpClient implements HttpClientInterface
{
    /** @var list<array{request: Request, config: Config}> */
    private array $recordedRequests = [];

    /** @var array<string, mixed> */
    private array $responses = [];

    public function __construct(array $initialResponses = [])
    {
        foreach ($initialResponses as $endpoint => $response) {
            $this->fakeResponse($endpoint, $response);
        }
    }

    /**
     * Define a fake response for a specific endpoint (or '*' for all).
     */
    public function fakeResponse(string $endpoint, mixed $response): static
    {
        $this->responses[ltrim($endpoint, '/')] = $response;
        return $this;
    }

    public function send(Config $config, Request $request): Response
    {
        $this->recordedRequests[] = [
            'request' => $request,
            'config' => $config,
        ];

        $endpoint = $request->endpoint;

        // 1. Direct match
        if (array_key_exists($endpoint, $this->responses)) {
            return $this->formatResponse($this->responses[$endpoint], $request);
        }

        // 2. Wildcard match
        if (array_key_exists('*', $this->responses)) {
            return $this->formatResponse($this->responses['*'], $request);
        }

        // 3. Default synthetic successful response
        return new Response(200, [
            'ok' => true,
            'result' => true,
        ]);
    }

    public function download(
        Config $config,
        string $filePath,
        mixed $destination,
        ?callable $progress = null
    ): bool {
        $this->recordedRequests[] = [
            'request' => new Request("download/{$filePath}"),
            'config' => $config,
        ];

        if (is_resource($destination)) {
            fwrite($destination, 'fake-file-content');
        } elseif (is_string($destination)) {
            @file_put_contents($destination, 'fake-file-content');
        }

        if ($progress !== null) {
            $progress(17, 17, 100.0);
        }

        return true;
    }

    /**
     * @return list<array{request: Request, config: Config}>
     */
    public function recorded(?string $endpoint = null): array
    {
        if ($endpoint === null) {
            return $this->recordedRequests;
        }

        return array_values(array_filter(
            $this->recordedRequests,
            fn(array $entry) => $entry['request']->endpoint === $endpoint
        ));
    }

    public function hasSent(string $endpoint): bool
    {
        return !empty($this->recorded($endpoint));
    }

    public function clear(): void
    {
        $this->recordedRequests = [];
    }

    private function formatResponse(mixed $response, Request $request): Response
    {
        if ($response instanceof Closure) {
            $response = $response($request);
        }

        if ($response instanceof Response) {
            return $response;
        }

        if (is_array($response)) {
            if (!isset($response['ok'])) {
                return new Response(200, [
                    'ok' => true,
                    'result' => $response,
                ]);
            }
            $status = $response['ok'] ? 200 : 400;
            return new Response($status, $response);
        }

        return new Response(200, [
            'ok' => true,
            'result' => $response,
        ]);
    }
}
