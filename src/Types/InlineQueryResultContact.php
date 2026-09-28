<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\InlineQueryResultType;

/**
 * Represents a contact with a phone number. By default, this contact will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the contact.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcontact
 */
class InlineQueryResultContact extends InlineQueryResult
{
    /**
     * Type of the result, must be contact
     */
    #[Field('type', required: true)]
    private(set) InlineQueryResultType|string|null $type = null;

    /**
     * Unique identifier for this result, 1-64 Bytes
     */
    #[Field('id', required: true)]
    private(set) ?string $id = null;

    /**
     * Contact's phone number
     */
    #[Field('phone_number', required: true)]
    private(set) ?string $phoneNumber = null;

    /**
     * Contact's first name
     */
    #[Field('first_name', required: true)]
    private(set) ?string $firstName = null;

    /**
     * Optional. Contact's last name
     */
    #[Field('last_name', required: false)]
    private(set) ?string $lastName = null;

    /**
     * Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
     */
    #[Field('vcard', required: false)]
    private(set) ?string $vcard = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the contact
     */
    #[Field('input_message_content', required: false)]
    private(set) ?InputMessageContent $inputMessageContent = null;

    /**
     * Optional. Url of the thumbnail for the result
     */
    #[Field('thumbnail_url', required: false)]
    private(set) ?string $thumbnailUrl = null;

    /**
     * Optional. Thumbnail width
     */
    #[Field('thumbnail_width', required: false)]
    private(set) ?int $thumbnailWidth = null;

    /**
     * Optional. Thumbnail height
     */
    #[Field('thumbnail_height', required: false)]
    private(set) ?int $thumbnailHeight = null;

}
