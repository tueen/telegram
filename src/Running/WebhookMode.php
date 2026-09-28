<?php

declare(strict_types=1);

namespace Tueen\Telegram\Running;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

class WebhookMode implements RunningModeInterface
{
    public function __construct(
        private ?string $secretToken = null,
        private ?string $rawInput = null,
        private ?array $headers = null
    ) {}

    /**
     * Creates a WebhookMode instance pre-configured from a PSR-7 ServerRequest.
     */
    public static function fromPsrRequest(ServerRequestInterface $request, ?string $secretToken = null): self
    {
        return new self(
            secretToken: $secretToken,
            rawInput: (string)$request->getBody(),
            headers: $request->getHeaders()
        );
    }

    public function setSecretToken(?string $secretToken): static
    {
        $this->secretToken = $secretToken;
        return $this;
    }

    public function setRawInput(?string $rawInput): static
    {
        $this->rawInput = $rawInput;
        return $this;
    }

    public function setHeaders(?array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Resolves and validates the incoming update from webhook payload.
     */
    public function resolveUpdate(Telegram $bot): Update
    {
        $isValidToken = $this->validateSecretToken();
        unset($isValidToken);

        $rawInput = $this->rawInput ?? file_get_contents('php://input');

        if (empty($rawInput)) {
            throw new TelegramException("Empty webhook payload received.");
        }

        $data = json_decode($rawInput, true);
        if (!is_array($data)) {
            throw new TelegramException("Invalid JSON payload in webhook: " . json_last_error_msg());
        }

        return new Update($data);
    }

    /**
     * Alias for resolveUpdate().
     */
    public function getUpdate(Telegram $bot): Update
    {
        return $this->resolveUpdate($bot);
    }

    /**
     * Process the update, executing handler if provided, and returning the Update.
     */
    public function processUpdate(Telegram $bot, ?callable $handler = null): Update
    {
        $update = $this->resolveUpdate($bot);
        $bot->setUpdate($update);

        if ($handler !== null) {
            $handler($update);
        }

        return $update;
    }

    /**
     * Validates the X-Telegram-Bot-Api-Secret-Token header if a secret token is configured.
     */
    #[\NoDiscard]
    public function validateSecretToken(): bool
    {
        if ($this->secretToken === null) {
            return true;
        }

        $receivedToken = $this->resolveReceivedSecretToken();

        if ($receivedToken === null || !hash_equals($this->secretToken, $receivedToken)) {
            throw new TelegramException("Invalid or missing Telegram webhook secret token.");
        }

        return true;
    }

    /**
     * Validates whether a given webhook URL complies with Telegram HTTPS requirements.
     */
    #[\NoDiscard]
    public function validateWebhookUrl(string $url): bool
    {
        try {
            if (class_exists(\Uri\Rfc3986\Uri::class)) {
                $uri = new \Uri\Rfc3986\Uri($url);
                return $uri->getScheme() === 'https' && !empty($uri->getHost());
            }

            $parts = parse_url($url);
            return is_array($parts) && ($parts['scheme'] ?? null) === 'https' && !empty($parts['host'] ?? null);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Process an incoming PSR-7 ServerRequest, execute bot handlers, and return a PSR-7 Response.
     */
    public function processPsrRequest(ServerRequestInterface $request, Telegram $bot, mixed ...$handlers): ResponseInterface
    {
        $this->rawInput = (string)$request->getBody();
        $this->headers = $request->getHeaders();

        $bot->run(...$handlers);

        return new Psr7Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: json_encode(['ok' => true])
        );
    }

    /**
     * Sends an immediate HTTP 200 OK response to Telegram and flushes buffer if possible.
     */
    public function safeResponse(): void
    {
        if (PHP_SAPI !== 'cli' && !headers_sent()) {
            http_response_code(200);
            header('Content-Type: application/json');
            header('Connection: close');
            echo json_encode(['ok' => true]);
        }

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif (PHP_SAPI !== 'cli' && ob_get_level() > 0) {
            ob_end_flush();
            flush();
        }
    }

    private function resolveReceivedSecretToken(): ?string
    {
        if ($this->headers !== null) {
            foreach ($this->headers as $k => $v) {
                if (strcasecmp((string)$k, 'X-Telegram-Bot-Api-Secret-Token') === 0) {
                    return is_array($v) ? ($v[0] ?? null) : (string)$v;
                }
            }
        }

        if (isset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'])) {
            return (string)$_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'];
        }

        return null;
    }
}
