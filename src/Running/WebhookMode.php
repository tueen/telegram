<?php

declare(strict_types=1);

namespace Tueen\Telegram\Running;

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
    public function getUpdate(Telegram $telegram): Update
    {
        $this->validateSecretToken();

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
     * Process the update, executing handler if provided, and returning the Update.
     */
    public function processUpdate(Telegram $telegram, ?callable $handler = null): Update
    {
        $update = $this->getUpdate($telegram);

        if ($handler !== null) {
            $handler($update);
        }

        return $update;
    }

    /**
     * Validates the X-Telegram-Bot-Api-Secret-Token header if a secret token is configured.
     */
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
     * Sends an immediate HTTP 200 OK response to Telegram and flushes buffer if possible.
     */
    public function safeResponse(): void
    {
        if (!headers_sent()) {
            http_response_code(200);
            header('Content-Type: application/json');
            header('Connection: close');
            echo json_encode(['ok' => true]);
        }

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif (ob_get_level() > 0) {
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
