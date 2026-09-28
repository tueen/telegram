<?php

declare(strict_types=1);

namespace Tueen\Telegram\Client;

use Psr\Http\Message\StreamInterface;
use Tueen\Telegram\Config;
use Tueen\Telegram\Exceptions\NetworkException;
use Tueen\Telegram\Types\Custom\InputFile;

/**
 * Ultra-fast native cURL HTTP client utilizing PHP 8.5 persistent share handles
 * for connection, DNS, and TLS session reuse.
 */
class CurlHttpClient implements HttpClientInterface
{
    private static mixed $persistentShareHandle = null;

    public function __construct(
        private readonly bool $usePersistentShare = true
    ) {
        if ($this->usePersistentShare && self::$persistentShareHandle === null && function_exists('curl_share_init_persistent')) {
            self::$persistentShareHandle = curl_share_init_persistent([
                CURL_LOCK_DATA_DNS,
                CURL_LOCK_DATA_CONNECT,
                CURL_LOCK_DATA_SSL_SESSION,
            ]);
        }
    }

    public function send(Config $config, Request $request): Response
    {
        $url = $config->getBaseApiUrl() . '/' . $request->endpoint;
        $ch = curl_init($url);

        if ($ch === false) {
            throw new NetworkException("Failed to initialize cURL handle.");
        }

        if ($this->usePersistentShare && self::$persistentShareHandle !== null) {
            curl_setopt($ch, CURLOPT_SHARE, self::$persistentShareHandle);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, (int)($config->timeout * 1000));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, (int)($config->connectTimeout * 1000));

        if ($config->proxy !== null) {
            curl_setopt($ch, CURLOPT_PROXY, $config->proxy);
        }

        $headers = [];

        $uploadCb = $request->uploadProgress ?? $config->uploadProgress;
        $downloadCb = $request->downloadProgress ?? $config->downloadProgress;

        if ($uploadCb !== null || $downloadCb !== null) {
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function (
                $resource,
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
            });
        }

        if ($request->httpMethod === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($request->isMultipart()) {
                $postData = [];
                foreach ($request->parameters as $name => $contents) {
                    $postData[(string)$name] = is_scalar($contents) ? (string)$contents : json_encode($contents);
                }
                foreach ($request->files as $name => $file) {
                    if ($file instanceof InputFile) {
                        $rawContents = $file->getContents();
                        if (is_resource($rawContents)) {
                            $stringData = stream_get_contents($rawContents);
                        } elseif ($rawContents instanceof StreamInterface) {
                            $stringData = (string)$rawContents;
                        } else {
                            $stringData = (string)$rawContents;
                        }
                        $postData[(string)$name] = new \CURLStringFile(
                            $stringData,
                            $file->getFilename(),
                            $file->getContentType() ?? 'application/octet-stream'
                        );
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            } else {
                $jsonData = json_encode($request->parameters);
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Content-Length: ' . strlen((string)$jsonData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            }
        } elseif ($request->httpMethod === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $rawResponse = curl_exec($ch);

        if ($rawResponse === false) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);
            throw new NetworkException("cURL error ({$errno}): {$error}", $errno);
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $rawHeaders = substr((string)$rawResponse, 0, $headerSize);
        $rawBody = substr((string)$rawResponse, $headerSize);
        $parsedHeaders = $this->parseHeaders($rawHeaders);
        $data = json_decode($rawBody, true) ?? [];

        return new Response(
            statusCode: (int)$statusCode,
            data: $data,
            rawBody: $rawBody,
            headers: $parsedHeaders
        );
    }

    public function download(Config $config, string $fileUrl, mixed $destination, ?callable $progress = null): bool
    {
        if (!str_starts_with($fileUrl, 'http://') && !str_starts_with($fileUrl, 'https://')) {
            $url = $config->getBaseFileUrl() . '/' . ltrim($fileUrl, '/');
        } else {
            $url = $fileUrl;
        }

        $closeHandleOnFinish = false;
        if (is_string($destination)) {
            $fp = fopen($destination, 'wb');
            if ($fp === false) {
                throw new NetworkException("Failed to open destination file for writing: {$destination}");
            }
            $closeHandleOnFinish = true;
        } elseif (is_resource($destination)) {
            $fp = $destination;
        } else {
            throw new NetworkException("Invalid destination for download. Expected file path or stream resource.");
        }

        $ch = curl_init($url);
        if ($ch === false) {
            if ($closeHandleOnFinish) {
                fclose($fp);
            }
            throw new NetworkException("Failed to initialize cURL download.");
        }

        if ($this->usePersistentShare && self::$persistentShareHandle !== null) {
            curl_setopt($ch, CURLOPT_SHARE, self::$persistentShareHandle);
        }

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, (int)($config->timeout * 1000));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, (int)($config->connectTimeout * 1000));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if ($config->proxy !== null) {
            curl_setopt($ch, CURLOPT_PROXY, $config->proxy);
        }

        $downloadCb = $progress ?? $config->downloadProgress;
        if ($downloadCb !== null) {
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function (
                $resource,
                int $dlTotal,
                int $dlBytes,
                int $ulTotal,
                int $ulBytes
            ) use ($downloadCb) {
                if ($dlBytes > 0) {
                    $pct = $dlTotal > 0 ? round(($dlBytes / $dlTotal) * 100, 2) : 0.0;
                    $downloadCb($dlBytes, $dlTotal, $pct);
                }
            });
        }

        $success = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($closeHandleOnFinish) {
            fclose($fp);
        }

        if ($success === false) {
            throw new NetworkException("File download cURL error ({$errno}): {$error}", $errno);
        }

        return $statusCode === 200;
    }

    private function parseHeaders(string $headerContent): array
    {
        $headers = [];
        $lines = explode("\r\n", trim($headerContent));

        foreach ($lines as $line) {
            if (!str_contains($line, ':')) {
                continue;
            }
            [$name, $value] = explode(':', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (isset($headers[$name])) {
                if (is_array($headers[$name])) {
                    $headers[$name][] = $value;
                } else {
                    $headers[$name] = [$headers[$name], $value];
                }
            } else {
                $headers[$name] = [$value];
            }
        }

        return $headers;
    }
}
