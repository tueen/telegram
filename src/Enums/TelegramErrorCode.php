<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

use Tueen\Telegram\Exceptions\ApiException;
use Tueen\Telegram\Exceptions\BadRequestException;
use Tueen\Telegram\Exceptions\BotBlockedException;
use Tueen\Telegram\Exceptions\BotCantInitiateConversationException;
use Tueen\Telegram\Exceptions\BotKickedException;
use Tueen\Telegram\Exceptions\BusinessConnectionNotFoundException;
use Tueen\Telegram\Exceptions\BusinessConnectionRevokedException;
use Tueen\Telegram\Exceptions\ButtonDataInvalidException;
use Tueen\Telegram\Exceptions\ButtonUrlInvalidException;
use Tueen\Telegram\Exceptions\CantParseEntitiesException;
use Tueen\Telegram\Exceptions\ChatNotFoundException;
use Tueen\Telegram\Exceptions\CommandInvalidException;
use Tueen\Telegram\Exceptions\CommandsListEmptyException;
use Tueen\Telegram\Exceptions\CommandTooLongException;
use Tueen\Telegram\Exceptions\DateInPastException;
use Tueen\Telegram\Exceptions\DateTooFarException;
use Tueen\Telegram\Exceptions\FileTooLargeException;
use Tueen\Telegram\Exceptions\ForbiddenException;
use Tueen\Telegram\Exceptions\InvalidLanguageCodeException;
use Tueen\Telegram\Exceptions\MediaEmptyException;
use Tueen\Telegram\Exceptions\MessageCantBeDeletedException;
use Tueen\Telegram\Exceptions\MessageCantBeEditedException;
use Tueen\Telegram\Exceptions\MessageEmptyException;
use Tueen\Telegram\Exceptions\MessageIdInvalidException;
use Tueen\Telegram\Exceptions\MessageNotFoundException;
use Tueen\Telegram\Exceptions\MessageNotModifiedException;
use Tueen\Telegram\Exceptions\MessageTooLongException;
use Tueen\Telegram\Exceptions\NotEnoughRightsException;
use Tueen\Telegram\Exceptions\PhotoInvalidDimensionsException;
use Tueen\Telegram\Exceptions\QueryIdInvalidException;
use Tueen\Telegram\Exceptions\RateLimitException;
use Tueen\Telegram\Exceptions\ReplyMarkupInvalidException;
use Tueen\Telegram\Exceptions\ReplyMessageNotFoundException;
use Tueen\Telegram\Exceptions\StarsAmountInvalidException;
use Tueen\Telegram\Exceptions\StickerDimensionsInvalidException;
use Tueen\Telegram\Exceptions\StickerEmojiInvalidException;
use Tueen\Telegram\Exceptions\StickerSetInvalidException;
use Tueen\Telegram\Exceptions\TerminatedByOtherGetUpdatesException;
use Tueen\Telegram\Exceptions\TooManyCommandsException;
use Tueen\Telegram\Exceptions\TopicClosedException;
use Tueen\Telegram\Exceptions\TopicDeletedException;
use Tueen\Telegram\Exceptions\TopicNotModifiedException;
use Tueen\Telegram\Exceptions\UnauthorizedException;
use Tueen\Telegram\Exceptions\UserAlreadyParticipantException;
use Tueen\Telegram\Exceptions\UserCantBeVerifiedException;
use Tueen\Telegram\Exceptions\UserDeactivatedException;
use Tueen\Telegram\Exceptions\UserNotFoundException;
use Tueen\Telegram\Exceptions\VoiceMessagesForbiddenException;
use Tueen\Telegram\Exceptions\WebhookActiveConflictException;
use Tueen\Telegram\Exceptions\WrongFileTypeException;

/**
 * Standard Telegram Bot API error codes catalog.
 */
enum TelegramErrorCode: string
{
    // 401 Unauthorized
    case Unauthorized = 'unauthorized';

    // 403 Forbidden
    case BotBlocked = 'bot_blocked';
    case BotKicked = 'bot_kicked';
    case UserDeactivated = 'user_deactivated';
    case BotCantInitiateConversation = 'bot_cant_initiate_conversation';
    case BusinessConnectionRevoked = 'business_connection_revoked';
    case NotEnoughRights = 'not_enough_rights';

    // 409 Conflict
    case ConflictWebhookActive = 'conflict_webhook_active';
    case ConflictTerminatedByOtherGetUpdates = 'conflict_terminated_by_other_get_updates';

    // 413 File too large
    case FileTooLarge = 'file_too_large';

    // 429 Too Many Requests
    case FloodWait = 'flood_wait';

    // 400 Bad Request: Chat & User
    case ChatNotFound = 'chat_not_found';
    case UserNotFound = 'user_not_found';
    case UserAlreadyParticipant = 'user_already_participant';
    case UserCantBeVerified = 'user_cant_be_verified';

    // 400 Bad Request: Messages & Formatting
    case MessageNotModified = 'message_not_modified';
    case MessageNotFound = 'message_not_found';
    case MessageCantBeDeleted = 'message_cant_be_deleted';
    case MessageCantBeEdited = 'message_cant_be_edited';
    case MessageEmpty = 'message_empty';
    case MessageTooLong = 'message_too_long';
    case CantParseEntities = 'cant_parse_entities';
    case ReplyMessageNotFound = 'reply_message_not_found';
    case MessageIdInvalid = 'message_id_invalid';

    // 400 Bad Request: Topics & Forums
    case TopicNotModified = 'topic_not_modified';
    case TopicClosed = 'topic_closed';
    case TopicDeleted = 'topic_deleted';

    // 400 Bad Request: Media & Files
    case WrongFileType = 'wrong_file_type';
    case PhotoInvalidDimensions = 'photo_invalid_dimensions';
    case VoiceMessagesForbidden = 'voice_messages_forbidden';
    case MediaEmpty = 'media_empty';

    // 400 Bad Request: Keyboards & Buttons
    case ButtonDataInvalid = 'button_data_invalid';
    case ButtonUrlInvalid = 'button_url_invalid';
    case ReplyMarkupInvalid = 'reply_markup_invalid';

    // 400 Bad Request: Commands
    case CommandsListEmpty = 'commands_list_empty';
    case TooManyCommands = 'too_many_commands';
    case CommandTooLong = 'command_too_long';
    case CommandInvalid = 'command_invalid';
    case InvalidLanguageCode = 'invalid_language_code';

    // 400 Bad Request: Queries & Connections
    case QueryIdInvalid = 'query_id_invalid';
    case BusinessConnectionNotFound = 'business_connection_not_found';

    // 400 Bad Request: Stickers
    case StickerSetInvalid = 'stickerset_invalid';
    case StickerEmojiInvalid = 'sticker_emoji_invalid';
    case StickerDimensionsInvalid = 'sticker_dimensions_invalid';

    // 400 Bad Request: Stars & Dates
    case StarsAmountInvalid = 'stars_amount_invalid';
    case DateTooFar = 'date_too_far';
    case DateInPast = 'date_in_past';

    // Generic / Fallback
    case Unknown = 'unknown';

    /**
     * Associated HTTP status code for this error.
     */
    public function httpStatus(): int
    {
        return match ($this) {
            self::Unauthorized => 401,
            self::BotBlocked,
            self::BotKicked,
            self::UserDeactivated,
            self::BotCantInitiateConversation,
            self::BusinessConnectionRevoked,
            self::NotEnoughRights => 403,
            self::ConflictWebhookActive,
            self::ConflictTerminatedByOtherGetUpdates => 409,
            self::FileTooLarge => 413,
            self::FloodWait => 429,
            self::Unknown => 0,
            default => 400,
        };
    }

    /**
     * Associated Typed Exception class for this error.
     *
     * @return class-string<ApiException>
     */
    public function exceptionClass(): string
    {
        return match ($this) {
            self::Unauthorized => UnauthorizedException::class,
            self::BotBlocked => BotBlockedException::class,
            self::BotKicked => BotKickedException::class,
            self::UserDeactivated => UserDeactivatedException::class,
            self::BotCantInitiateConversation => BotCantInitiateConversationException::class,
            self::BusinessConnectionRevoked => BusinessConnectionRevokedException::class,
            self::NotEnoughRights => NotEnoughRightsException::class,
            self::ConflictWebhookActive => WebhookActiveConflictException::class,
            self::ConflictTerminatedByOtherGetUpdates => TerminatedByOtherGetUpdatesException::class,
            self::FileTooLarge => FileTooLargeException::class,
            self::FloodWait => RateLimitException::class,
            self::ChatNotFound => ChatNotFoundException::class,
            self::UserNotFound => UserNotFoundException::class,
            self::UserAlreadyParticipant => UserAlreadyParticipantException::class,
            self::UserCantBeVerified => UserCantBeVerifiedException::class,
            self::MessageNotModified => MessageNotModifiedException::class,
            self::MessageNotFound => MessageNotFoundException::class,
            self::MessageCantBeDeleted => MessageCantBeDeletedException::class,
            self::MessageCantBeEdited => MessageCantBeEditedException::class,
            self::MessageEmpty => MessageEmptyException::class,
            self::MessageTooLong => MessageTooLongException::class,
            self::CantParseEntities => CantParseEntitiesException::class,
            self::ReplyMessageNotFound => ReplyMessageNotFoundException::class,
            self::MessageIdInvalid => MessageIdInvalidException::class,
            self::TopicNotModified => TopicNotModifiedException::class,
            self::TopicClosed => TopicClosedException::class,
            self::TopicDeleted => TopicDeletedException::class,
            self::WrongFileType => WrongFileTypeException::class,
            self::PhotoInvalidDimensions => PhotoInvalidDimensionsException::class,
            self::VoiceMessagesForbidden => VoiceMessagesForbiddenException::class,
            self::MediaEmpty => MediaEmptyException::class,
            self::ButtonDataInvalid => ButtonDataInvalidException::class,
            self::ButtonUrlInvalid => ButtonUrlInvalidException::class,
            self::ReplyMarkupInvalid => ReplyMarkupInvalidException::class,
            self::CommandsListEmpty => CommandsListEmptyException::class,
            self::TooManyCommands => TooManyCommandsException::class,
            self::CommandTooLong => CommandTooLongException::class,
            self::CommandInvalid => CommandInvalidException::class,
            self::InvalidLanguageCode => InvalidLanguageCodeException::class,
            self::QueryIdInvalid => QueryIdInvalidException::class,
            self::BusinessConnectionNotFound => BusinessConnectionNotFoundException::class,
            self::StickerSetInvalid => StickerSetInvalidException::class,
            self::StickerEmojiInvalid => StickerEmojiInvalidException::class,
            self::StickerDimensionsInvalid => StickerDimensionsInvalidException::class,
            self::StarsAmountInvalid => StarsAmountInvalidException::class,
            self::DateTooFar => DateTooFarException::class,
            self::DateInPast => DateInPastException::class,
            self::Unknown => ApiException::class,
        };
    }
}
