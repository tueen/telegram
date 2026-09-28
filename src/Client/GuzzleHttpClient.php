<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\NetworkException;
use Tueen\Telegram\Types\Custom\InputFile;

class GuzzleHttpClient implements HttpClientInterface
{
    private ?GuzzleClient $guzzle = null;

    public function __construct(?GuzzleClient $guzzle = null)
    {
        $this->guzzle = $guzzle;
    }

    private function getClient(Config $config): GuzzleClient
    {
        if ($this->guzzle !== null) {
            return $this->guzzle;
        }

        $options = [
            'timeout' => $config->timeout,
            'connect_timeout' => $config->connectTimeout,
            'http_errors' => false,
        ];

        if ($config->proxy !== null) {
            $options['proxy'] = $config->proxy;
        }

        return $this->guzzle = new GuzzleClient($options);
    }

    public function send(Config $config, Request $request): Response
    {
        $client = $this->getClient($config);
        $url = $config->getBaseApiUrl() . '/' . $request->endpoint;

        $options = [];

        // Per-request timeout override
        if ($request->timeout !== null) {
            $options['timeout'] = $request->timeout;
        }
        if ($request->connectTimeout !== null) {
            $options['connect_timeout'] = $request->connectTimeout;
        }

        // Setup progress callback if uploadProgress is set
        $uploadCb = $request->uploadProgress ?? $config->uploadProgress;
        $downloadCb = $request->downloadProgress ?? $config->downloadProgress;

        if ($uploadCb !== null || $downloadCb !== null) {
            $options[RequestOptions::PROGRESS] = function (
                int $dlTotal,
                int $dlBytes,
                int $ulTotal,
                int $ulBytes
            ) use ($uploadCb, $downloadCb) {
                if ($uploadCb !== null && $ulBytes > 0) {
                    $pct = $ulTotal > 0 ? round(($ulBytes / $ulTotal) * 100, 2) : 0.0;
                    $uploadCb($ulBytes, $ulTotal, $pct);
                }
                if ($downloadCb !== null && $dlBytes > 0) {
                    $pct = $dlTotal > 0 ? round(($dlBytes / $dlTotal) * 100, 2) : 0.0;
                    $downloadCb($dlBytes, $dlTotal, $pct);
                }
            };
        }

        if ($request->isMultipart()) {
            $multipart = [];
            foreach ($request->parameters as $name => $contents) {
                if (is_bool($contents)) {
                    $stringContents = $contents ? 'true' : 'false';
                } elseif (is_array($contents)) {
                    $stringContents = json_encode($contents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    $stringContents = (string)$contents;
                }

                $multipart[] = [
                    'name' => (string)$name,
                    'contents' => $stringContents,
                ];
            }
            foreach ($request->files as $name => $file) {
                if ($file instanceof InputFile) {
                    $multipart[] = $file->toMultipart((string)$name);
                }
            }
            $options[RequestOptions::MULTIPART] = $multipart;
        } else {
            $options[RequestOptions::JSON] = $request->parameters;
        }

        try {
            $response = $client->request($request->httpMethod, $url, $options);
            return $this->parseResponse($response);
        } catch (ConnectException $e) {
            throw new NetworkException("Failed to connect to Telegram API: " . $e->getMessage(), 0, $e);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $this->parseResponse($e->getResponse());
            }
            throw new NetworkException("HTTP request failed: " . $e->getMessage(), 0, $e);
        } catch (\Throwable $e) {
            throw new NetworkException("Unexpected network error: " . $e->getMessage(), 0, $e);
        }
    }

    public function download(Config $config, string $fileUrl, mixed $destination, ?callable $progress = null): bool
    {
        $client = $this->getClient($config);

        // If fileUrl is relative (e.g. photos/file_1.jpg), prepend base file url
        if (!str_starts_with($fileUrl, 'http://') && !str_starts_with($fileUrl, 'https://')) {
            $url = $config->getBaseFileUrl() . '/' . ltrim($fileUrl, '/');
        } else {
            $url = $fileUrl;
        }

        $options = [
            RequestOptions::SINK => $destination,
        ];

        $downloadCb = $progress ?? $config->downloadProgress;
        if ($downloadCb !== null) {
            $options[RequestOptions::PROGRESS] = function (
                int $dlTotal,
                int $dlBytes,
                int $ulTotal,
                int $ulBytes
            ) use ($downloadCb) {
                if ($dlBytes > 0) {
                    $pct = $dlTotal > 0 ? round(($dlBytes / $dlTotal) * 100, 2) : 0.0;
                    $downloadCb($dlBytes, $dlTotal, $pct);
                }
            };
        }

        try {
            $response = $client->request('GET', $url, $options);
            return $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            throw new NetworkException("File download failed: " . $e->getMessage(), 0, $e);
        }
    }

    private function parseResponse(ResponseInterface $psrResponse): Response
    {
        $statusCode = $psrResponse->getStatusCode();
        $rawBody = (string)$psrResponse->getBody();
        $data = json_decode($rawBody, true) ?? [];

        return new Response(
            statusCode: $statusCode,
            data: $data,
            rawBody: $rawBody,
            headers: $psrResponse->getHeaders()
        );
    }
}
