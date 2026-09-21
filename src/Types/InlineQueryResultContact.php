<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Enums\InlineQueryResultType;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\InputMessageContent;

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
    public private(set) InlineQueryResultType|string $type;

    /**
     * Unique identifier for this result, 1-64 Bytes
     */
    #[Field('id', required: true)]
    public private(set) string $id;

    /**
     * Contact's phone number
     */
    #[Field('phone_number', required: true)]
    public private(set) string $phoneNumber;

    /**
     * Contact's first name
     */
    #[Field('first_name', required: true)]
    public private(set) string $firstName;

    /**
     * Optional. Contact's last name
     */
    #[Field('last_name', required: false)]
    public private(set) ?string $lastName = null;

    /**
     * Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
     */
    #[Field('vcard', required: false)]
    public private(set) ?string $vcard = null;

    /**
     * Optional. Inline keyboard attached to the message
     */
    #[Field('reply_markup', required: false)]
    public private(set) ?InlineKeyboardMarkup $replyMarkup = null;

    /**
     * Optional. Content of the message to be sent instead of the contact
     */
    #[Field('input_message_content', required: false)]
    public private(set) ?InputMessageContent $inputMessageContent = null;

    /**
     * Optional. Url of the thumbnail for the result
     */
    #[Field('thumbnail_url', required: false)]
    public private(set) ?string $thumbnailUrl = null;

    /**
     * Optional. Thumbnail width
     */
    #[Field('thumbnail_width', required: false)]
    public private(set) ?int $thumbnailWidth = null;

    /**
     * Optional. Thumbnail height
     */
    #[Field('thumbnail_height', required: false)]
    public private(set) ?int $thumbnailHeight = null;

}
