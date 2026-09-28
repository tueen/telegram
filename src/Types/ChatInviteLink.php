<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Represents an invite link for a chat.
 *
 * @link https://core.telegram.org/bots/api#chatinvitelink
 */
class ChatInviteLink extends Type
{
    /**
     * The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with "...".
     */
    #[Field('invite_link', required: true)]
    private(set) string $inviteLink;

    /**
     * Creator of the link
     */
    #[Field('creator', required: true)]
    private(set) User $creator;

    /**
     * True, if users joining the chat via the link need to be approved by chat administrators
     */
    #[Field('creates_join_request', required: true)]
    private(set) bool $createsJoinRequest;

    /**
     * True, if the link is primary
     */
    #[Field('is_primary', required: true)]
    private(set) bool $isPrimary;

    /**
     * True, if the link is revoked
     */
    #[Field('is_revoked', required: true)]
    private(set) bool $isRevoked;

    /**
     * Optional. Invite link name
     */
    #[Field('name', required: false)]
    private(set) ?string $name = null;

    /**
     * Optional. Point in time (Unix timestamp) when the link will expire or has been expired
     */
    #[Field('expire_date', required: false)]
    private(set) ?int $expireDate = null;

    /**
     * Optional. The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
     */
    #[Field('member_limit', required: false)]
    private(set) ?int $memberLimit = null;

    /**
     * Optional. Number of pending join requests created using this link
     */
    #[Field('pending_join_request_count', required: false)]
    private(set) ?int $pendingJoinRequestCount = null;

    /**
     * Optional. The number of seconds the subscription will be active for before the next payment
     */
    #[Field('subscription_period', required: false)]
    private(set) ?int $subscriptionPeriod = null;

    /**
     * Optional. The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat using the link
     */
    #[Field('subscription_price', required: false)]
    private(set) ?int $subscriptionPrice = null;

}
