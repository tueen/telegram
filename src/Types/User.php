<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\Concerns\HasUserHelpers;

/**
 * This object represents a Telegram user or bot.
 *
 * @link https://core.telegram.org/bots/api#user
 */
class User extends Type
{
    use HasUserHelpers;

    /**
     * Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    #[Field('id', required: true)]
    private(set) ?int $id = null;

    /**
     * True, if this user is a bot
     */
    #[Field('is_bot', required: true)]
    private(set) ?bool $isBot = null;

    /**
     * User's or bot's first name
     */
    #[Field('first_name', required: true)]
    private(set) ?string $firstName = null;

    /**
     * Optional. User's or bot's last name
     */
    #[Field('last_name', required: false)]
    private(set) ?string $lastName = null;

    /**
     * Optional. User's or bot's username
     */
    #[Field('username', required: false)]
    private(set) ?string $username = null;

    /**
     * Optional. IETF language tag of the user's language
     */
    #[Field('language_code', required: false)]
    private(set) ?string $languageCode = null;

    /**
     * Optional. True, if this user is a Telegram Premium user
     */
    #[Field('is_premium', required: false)]
    private(set) ?bool $isPremium = null;

    /**
     * Optional. True, if this user added the bot to the attachment menu
     */
    #[Field('added_to_attachment_menu', required: false)]
    private(set) ?bool $addedToAttachmentMenu = null;

}
