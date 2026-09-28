<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;

/**
 * Describes the connection of the bot with a business account.
 *
 * @link https://core.telegram.org/bots/api#businessconnection
 */
class BusinessConnection extends Type
{
    /**
     * Unique identifier of the business connection
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Business account user that created the business connection
     */
    #[Field('user', required: true)]
    private(set) ?User $user = null;

    /**
     * Identifier of a private chat with the user who created the business connection. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('user_chat_id', required: true)]
    private(set) ?int $userChatId = null;

    /**
     * Date the connection was established in Unix time
     */
    #[Field('date', required: true)]
    private(set) ?int $date = null;

    /**
     * Optional. Rights of the business bot
     */
    #[Field('rights', required: false)]
    private(set) ?BusinessBotRights $rights = null;

    /**
     * True, if the connection is active
     */
    #[Field('is_enabled', required: true)]
    private(set) ?bool $isEnabled = null;

}
