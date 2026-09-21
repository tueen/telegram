<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

use Tueen\Telegram\Config;

interface HttpClientInterface
{
    /**
     * Sends an API request to Telegram.
     */
    public function send(Config $config, Request $request): Response;

    /**
     * Downloads a file from Telegram Bot API with optional progress callback.
     *
     * @param string $fileUrl Full URL or relative file path to download
     * @param resource|string $destination File path or stream resource to save to
     * @param callable|null $progress fn(int $downloadedBytes, int $totalBytes, float $percentage)
     */
    public function download(Config $config, string $fileUrl, mixed $destination, ?callable $progress = null): bool;
}
