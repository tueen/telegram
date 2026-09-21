<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Types\MessageOrigin;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\LinkPreviewOptions;
use Tueen\Telegram\Types\Animation;
use Tueen\Telegram\Types\Audio;
use Tueen\Telegram\Types\Document;
use Tueen\Telegram\Types\LivePhoto;
use Tueen\Telegram\Types\PaidMediaInfo;
use Tueen\Telegram\Types\PhotoSize;
use Tueen\Telegram\Types\Sticker;
use Tueen\Telegram\Types\Story;
use Tueen\Telegram\Types\Video;
use Tueen\Telegram\Types\VideoNote;
use Tueen\Telegram\Types\Voice;
use Tueen\Telegram\Types\Checklist;
use Tueen\Telegram\Types\Contact;
use Tueen\Telegram\Types\Dice;
use Tueen\Telegram\Types\Game;
use Tueen\Telegram\Types\Giveaway;
use Tueen\Telegram\Types\GiveawayWinners;
use Tueen\Telegram\Types\Invoice;
use Tueen\Telegram\Types\Location;
use Tueen\Telegram\Types\Poll;
use Tueen\Telegram\Types\Venue;

/**
 * This object contains information about a message that is being replied to, which may come from another chat or forum topic.
 *
 * @link https://core.telegram.org/bots/api#externalreplyinfo
 */
class ExternalReplyInfo extends Type
{
    /**
     * Origin of the message replied to by the given message
     */
    #[Field('origin', required: true)]
    public private(set) MessageOrigin $origin;

    /**
     * Optional. Chat the original message belongs to. Available only if the chat is a supergroup or a channel.
     */
    #[Field('chat', required: false)]
    public private(set) ?Chat $chat = null;

    /**
     * Optional. Unique message identifier inside the original chat. Available only if the original chat is a supergroup or a channel.
     */
    #[Field('message_id', required: false)]
    public private(set) ?int $messageId = null;

    /**
     * Optional. Options used for link preview generation for the original message, if it is a text message
     */
    #[Field('link_preview_options', required: false)]
    public private(set) ?LinkPreviewOptions $linkPreviewOptions = null;

    /**
     * Optional. Message is an animation, information about the animation
     */
    #[Field('animation', required: false)]
    public private(set) ?Animation $animation = null;

    /**
     * Optional. Message is an audio file, information about the file
     */
    #[Field('audio', required: false)]
    public private(set) ?Audio $audio = null;

    /**
     * Optional. Message is a general file, information about the file
     */
    #[Field('document', required: false)]
    public private(set) ?Document $document = null;

    /**
     * Optional. Message is a live photo, information about the live photo
     */
    #[Field('live_photo', required: false)]
    public private(set) ?LivePhoto $livePhoto = null;

    /**
     * Optional. Message contains paid media; information about the paid media
     */
    #[Field('paid_media', required: false)]
    public private(set) ?PaidMediaInfo $paidMedia = null;

    /**
     * Optional. Message is a photo, available sizes of the photo
     * @var PhotoSize[]|null
     */
    #[Field('photo', required: false)]
    #[ArrayOf(PhotoSize::class)]
    public private(set) ?array $photo = null;

    /**
     * Optional. Message is a sticker, information about the sticker
     */
    #[Field('sticker', required: false)]
    public private(set) ?Sticker $sticker = null;

    /**
     * Optional. Message is a forwarded story
     */
    #[Field('story', required: false)]
    public private(set) ?Story $story = null;

    /**
     * Optional. Message is a video, information about the video
     */
    #[Field('video', required: false)]
    public private(set) ?Video $video = null;

    /**
     * Optional. Message is a video note, information about the video message
     */
    #[Field('video_note', required: false)]
    public private(set) ?VideoNote $videoNote = null;

    /**
     * Optional. Message is a voice message, information about the file
     */
    #[Field('voice', required: false)]
    public private(set) ?Voice $voice = null;

    /**
     * Optional. True, if the message media is covered by a spoiler animation
     */
    #[Field('has_media_spoiler', required: false)]
    public private(set) ?bool $hasMediaSpoiler = null;

    /**
     * Optional. Message is a checklist
     */
    #[Field('checklist', required: false)]
    public private(set) ?Checklist $checklist = null;

    /**
     * Optional. Message is a shared contact, information about the contact
     */
    #[Field('contact', required: false)]
    public private(set) ?Contact $contact = null;

    /**
     * Optional. Message is a dice with random value
     */
    #[Field('dice', required: false)]
    public private(set) ?Dice $dice = null;

    /**
     * Optional. Message is a game, information about the game. More about games: https://core.telegram.org/bots/api#games
     */
    #[Field('game', required: false)]
    public private(set) ?Game $game = null;

    /**
     * Optional. Message is a scheduled giveaway, information about the giveaway
     */
    #[Field('giveaway', required: false)]
    public private(set) ?Giveaway $giveaway = null;

    /**
     * Optional. A giveaway with public winners was completed
     */
    #[Field('giveaway_winners', required: false)]
    public private(set) ?GiveawayWinners $giveawayWinners = null;

    /**
     * Optional. Message is an invoice for a payment, information about the invoice. More about payments: https://core.telegram.org/bots/api#payments
     */
    #[Field('invoice', required: false)]
    public private(set) ?Invoice $invoice = null;

    /**
     * Optional. Message is a shared location, information about the location
     */
    #[Field('location', required: false)]
    public private(set) ?Location $location = null;

    /**
     * Optional. Message is a native poll, information about the poll
     */
    #[Field('poll', required: false)]
    public private(set) ?Poll $poll = null;

    /**
     * Optional. Message is a venue, information about the venue
     */
    #[Field('venue', required: false)]
    public private(set) ?Venue $venue = null;

}
