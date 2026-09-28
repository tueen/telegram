<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\RequiresUpload;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\Custom\InputFile;

/**
 * Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request (a request with response HTTP status code different from 2XY), we will repeat the request and give up after a reasonable amount of attempts. Returns True on success.
 * If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header "X-Telegram-Bot-Api-Secret-Token" with the secret token as content.
 *
 * @link https://core.telegram.org/bots/api#setwebhook
 */
#[ApiMethod('setWebhook', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetWebhook extends Method
{
    /**
     * HTTPS URL to send updates to. Use an empty string to remove webhook integration.
     */
    #[Field('url', required: true)]
    public string $url;

    /**
     * Upload your public key certificate so that the root certificate in use can be checked. See our self-signed guide for details.
     */
    #[Field('certificate', required: false)]
    #[RequiresUpload]
    public ?InputFile $certificate = null;

    /**
     * The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS
     */
    #[Field('ip_address', required: false)]
    public ?string $ipAddress = null;

    /**
     * The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery, 1-100. Defaults to 40. Use lower values to limit the load on your bot's server, and higher values to increase your bot's throughput.
     */
    #[Field('max_connections', required: false)]
    public ?int $maxConnections = null;

    /**
     * A JSON-serialized list of the update types you want your bot to receive. For example, specify ["message", "edited_channel_post", "callback_query"] to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member, message_reaction, and message_reaction_count (default). If not specified, the previous setting will be used. Please note that this parameter doesn't affect updates created before the call to the setWebhook, so unwanted updates may be received for a short period of time.
     */
    #[Field('allowed_updates', required: false)]
    public ?array $allowedUpdates = null;

    /**
     * Pass True to drop all pending updates
     */
    #[Field('drop_pending_updates', required: false)]
    public ?bool $dropPendingUpdates = null;

    /**
     * A secret token to be sent in a header "X-Telegram-Bot-Api-Secret-Token" in every webhook request, 1-256 characters. Only characters A-Z, a-z, 0-9, _ and - are allowed. The header is useful to ensure that the request comes from a webhook set by you.
     */
    #[Field('secret_token', required: false)]
    public ?string $secretToken = null;

    public function __construct(
        string $url,
        ?InputFile $certificate = null,
        ?string $ipAddress = null,
        ?int $maxConnections = null,
        ?array $allowedUpdates = null,
        ?bool $dropPendingUpdates = null,
        ?string $secretToken = null,
        mixed ...$extra
    )
    {
        if ($url !== null) $this->url = $url;
        if ($certificate !== null) $this->certificate = $certificate;
        if ($ipAddress !== null) $this->ipAddress = $ipAddress;
        if ($maxConnections !== null) $this->maxConnections = $maxConnections;
        if ($allowedUpdates !== null) $this->allowedUpdates = $allowedUpdates;
        if ($dropPendingUpdates !== null) $this->dropPendingUpdates = $dropPendingUpdates;
        if ($secretToken !== null) $this->secretToken = $secretToken;
        if (!empty($extra)) $this->handleExtraParameters($extra);
    }
}
