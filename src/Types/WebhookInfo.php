<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes the current status of a webhook.
 *
 * @link https://core.telegram.org/bots/api#webhookinfo
 */
class WebhookInfo extends Type
{
    /**
     * Webhook URL, may be empty if webhook is not set up
     */
    #[Field('url', required: true)]
    private(set) string $url;

    /**
     * True, if a custom certificate was provided for webhook certificate checks
     */
    #[Field('has_custom_certificate', required: true)]
    private(set) bool $hasCustomCertificate;

    /**
     * Number of updates awaiting delivery
     */
    #[Field('pending_update_count', required: true)]
    private(set) int $pendingUpdateCount;

    /**
     * Optional. Currently used webhook IP address
     */
    #[Field('ip_address', required: false)]
    private(set) ?string $ipAddress = null;

    /**
     * Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook
     */
    #[Field('last_error_date', required: false)]
    private(set) ?int $lastErrorDate = null;

    /**
     * Optional. Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook
     */
    #[Field('last_error_message', required: false)]
    private(set) ?string $lastErrorMessage = null;

    /**
     * Optional. Unix time of the most recent error that happened when trying to synchronize available updates with Telegram datacenters
     */
    #[Field('last_synchronization_error_date', required: false)]
    private(set) ?int $lastSynchronizationErrorDate = null;

    /**
     * Optional. The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery
     */
    #[Field('max_connections', required: false)]
    private(set) ?int $maxConnections = null;

    /**
     * Optional. A list of update types the bot is subscribed to. Defaults to all update types except chat_member, message_reaction, and message_reaction_count.
     * @var String[]|null
     */
    #[Field('allowed_updates', required: false)]
    private(set) ?array $allowedUpdates = null;

}
