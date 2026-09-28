<?php

declare(strict_types=1);

namespace Tueen\Telegram\Exceptions;

use Throwable;
use Tueen\Telegram\Enums\TelegramErrorCode;

/**
 * Matches Telegram Bot API error descriptions and status codes to typed TelegramErrorCode and ApiException classes.
 */
final class ErrorMatcher
{
    /**
     * Cache of compiled rules: [ [status, pattern, TelegramErrorCode, ExceptionClass], ... ]
     *
     * @var list<array{status: int, pattern: string, code: TelegramErrorCode, class: class-string<ApiException>}>
     */
    private static array $rules = [
        // 429 RateLimit
        ['status' => 429, 'pattern' => '/too many requests:\s*retry after/i', 'code' => TelegramErrorCode::FloodWait, 'class' => RateLimitException::class],

        // 401 Unauthorized
        ['status' => 401, 'pattern' => '/\b(unauthorized|invalid bot token|invalid token specified)\b/i', 'code' => TelegramErrorCode::Unauthorized, 'class' => UnauthorizedException::class],

        // 413 File too large
        ['status' => 413, 'pattern' => '/(request entity too large|file is too big|file exceeds the size limit)/i', 'code' => TelegramErrorCode::FileTooLarge, 'class' => FileTooLargeException::class],

        // 409 Conflict
        ['status' => 409, 'pattern' => "/can't use getUpdates method while webhook is active/i", 'code' => TelegramErrorCode::ConflictWebhookActive, 'class' => WebhookActiveConflictException::class],
        ['status' => 409, 'pattern' => '/terminated by other getUpdates request/i', 'code' => TelegramErrorCode::ConflictTerminatedByOtherGetUpdates, 'class' => TerminatedByOtherGetUpdatesException::class],

        // 403 Forbidden
        ['status' => 403, 'pattern' => '/bot was blocked by the user/i', 'code' => TelegramErrorCode::BotBlocked, 'class' => BotBlockedException::class],
        ['status' => 403, 'pattern' => '/(bot was kicked|bot is not a member)/i', 'code' => TelegramErrorCode::BotKicked, 'class' => BotKickedException::class],
        ['status' => 403, 'pattern' => '/user is deactivated/i', 'code' => TelegramErrorCode::UserDeactivated, 'class' => UserDeactivatedException::class],
        ['status' => 403, 'pattern' => "/bot can't initiate conversation with a user/i", 'code' => TelegramErrorCode::BotCantInitiateConversation, 'class' => BotCantInitiateConversationException::class],
        ['status' => 403, 'pattern' => '/business connection revoked/i', 'code' => TelegramErrorCode::BusinessConnectionRevoked, 'class' => BusinessConnectionRevokedException::class],
        ['status' => 403, 'pattern' => '/(not enough rights|bot is not an administrator)/i', 'code' => TelegramErrorCode::NotEnoughRights, 'class' => NotEnoughRightsException::class],

        // 400 Bad Request: Chat & User
        ['status' => 400, 'pattern' => '/chat not found/i', 'code' => TelegramErrorCode::ChatNotFound, 'class' => ChatNotFoundException::class],
        ['status' => 400, 'pattern' => '/user not found/i', 'code' => TelegramErrorCode::UserNotFound, 'class' => UserNotFoundException::class],
        ['status' => 400, 'pattern' => '/USER_ALREADY_PARTICIPANT/i', 'code' => TelegramErrorCode::UserAlreadyParticipant, 'class' => UserAlreadyParticipantException::class],
        ['status' => 400, 'pattern' => "/user can't be verified/i", 'code' => TelegramErrorCode::UserCantBeVerified, 'class' => UserCantBeVerifiedException::class],

        // 400 Bad Request: Messages & Formatting
        ['status' => 400, 'pattern' => '/message is not modified/i', 'code' => TelegramErrorCode::MessageNotModified, 'class' => MessageNotModifiedException::class],
        ['status' => 400, 'pattern' => '/message( to delete| to forward)? not found/i', 'code' => TelegramErrorCode::MessageNotFound, 'class' => MessageNotFoundException::class],
        ['status' => 400, 'pattern' => "/message can't be deleted/i", 'code' => TelegramErrorCode::MessageCantBeDeleted, 'class' => MessageCantBeDeletedException::class],
        ['status' => 400, 'pattern' => "/message can't be edited/i", 'code' => TelegramErrorCode::MessageCantBeEdited, 'class' => MessageCantBeEditedException::class],
        ['status' => 400, 'pattern' => '/(message is empty|message text is empty)/i', 'code' => TelegramErrorCode::MessageEmpty, 'class' => MessageEmptyException::class],
        ['status' => 400, 'pattern' => '/(TEXT_TOO_LONG|message is too long)/i', 'code' => TelegramErrorCode::MessageTooLong, 'class' => MessageTooLongException::class],
        ['status' => 400, 'pattern' => "/(can't parse entities|ENTITY_)/i", 'code' => TelegramErrorCode::CantParseEntities, 'class' => CantParseEntitiesException::class],
        ['status' => 400, 'pattern' => '/REPLY_MESSAGE_NOT_FOUND/i', 'code' => TelegramErrorCode::ReplyMessageNotFound, 'class' => ReplyMessageNotFoundException::class],
        ['status' => 400, 'pattern' => '/MESSAGE_ID_INVALID/i', 'code' => TelegramErrorCode::MessageIdInvalid, 'class' => MessageIdInvalidException::class],

        // 400 Bad Request: Topics & Forums
        ['status' => 400, 'pattern' => '/TOPIC_NOT_MODIFIED/i', 'code' => TelegramErrorCode::TopicNotModified, 'class' => TopicNotModifiedException::class],
        ['status' => 400, 'pattern' => '/TOPIC_CLOSED/i', 'code' => TelegramErrorCode::TopicClosed, 'class' => TopicClosedException::class],
        ['status' => 400, 'pattern' => '/TOPIC_DELETED/i', 'code' => TelegramErrorCode::TopicDeleted, 'class' => TopicDeletedException::class],

        // 400 Bad Request: Media & Files
        ['status' => 400, 'pattern' => '/(wrong file type|FILE_PARTS_INVALID)/i', 'code' => TelegramErrorCode::WrongFileType, 'class' => WrongFileTypeException::class],
        ['status' => 400, 'pattern' => '/PHOTO_INVALID_DIMENSIONS/i', 'code' => TelegramErrorCode::PhotoInvalidDimensions, 'class' => PhotoInvalidDimensionsException::class],
        ['status' => 400, 'pattern' => '/VOICE_MESSAGES_FORBIDDEN/i', 'code' => TelegramErrorCode::VoiceMessagesForbidden, 'class' => VoiceMessagesForbiddenException::class],
        ['status' => 400, 'pattern' => '/MEDIA_EMPTY/i', 'code' => TelegramErrorCode::MediaEmpty, 'class' => MediaEmptyException::class],

        // 400 Bad Request: Keyboards & Buttons
        ['status' => 400, 'pattern' => '/BUTTON_DATA_INVALID/i', 'code' => TelegramErrorCode::ButtonDataInvalid, 'class' => ButtonDataInvalidException::class],
        ['status' => 400, 'pattern' => '/BUTTON_URL_INVALID/i', 'code' => TelegramErrorCode::ButtonUrlInvalid, 'class' => ButtonUrlInvalidException::class],
        ['status' => 400, 'pattern' => '/REPLY_MARKUP_INVALID/i', 'code' => TelegramErrorCode::ReplyMarkupInvalid, 'class' => ReplyMarkupInvalidException::class],

        // 400 Bad Request: Commands
        ['status' => 400, 'pattern' => '/commands list must be non-empty/i', 'code' => TelegramErrorCode::CommandsListEmpty, 'class' => CommandsListEmptyException::class],
        ['status' => 400, 'pattern' => '/too many commands/i', 'code' => TelegramErrorCode::TooManyCommands, 'class' => TooManyCommandsException::class],
        ['status' => 400, 'pattern' => '/command is too long/i', 'code' => TelegramErrorCode::CommandTooLong, 'class' => CommandTooLongException::class],
        ['status' => 400, 'pattern' => '/command must start with a letter/i', 'code' => TelegramErrorCode::CommandInvalid, 'class' => CommandInvalidException::class],
        ['status' => 400, 'pattern' => '/language_code must be a 2-letter ISO 639-1 code/i', 'code' => TelegramErrorCode::InvalidLanguageCode, 'class' => InvalidLanguageCodeException::class],

        // 400 Bad Request: Queries & Connections
        ['status' => 400, 'pattern' => '/(query is too old|QUERY_ID_INVALID)/i', 'code' => TelegramErrorCode::QueryIdInvalid, 'class' => QueryIdInvalidException::class],
        ['status' => 400, 'pattern' => '/business_connection_id not found/i', 'code' => TelegramErrorCode::BusinessConnectionNotFound, 'class' => BusinessConnectionNotFoundException::class],

        // 400 Bad Request: Stickers
        ['status' => 400, 'pattern' => '/STICKERSET_INVALID/i', 'code' => TelegramErrorCode::StickerSetInvalid, 'class' => StickerSetInvalidException::class],
        ['status' => 400, 'pattern' => '/STICKER_EMOJI_INVALID/i', 'code' => TelegramErrorCode::StickerEmojiInvalid, 'class' => StickerEmojiInvalidException::class],
        ['status' => 400, 'pattern' => '/(STICKER_PNG_DIMENSIONS|STICKER_TGS_DIMENSIONS)/i', 'code' => TelegramErrorCode::StickerDimensionsInvalid, 'class' => StickerDimensionsInvalidException::class],

        // 400 Bad Request: Stars & Dates
        ['status' => 400, 'pattern' => '/STARS_AMOUNT_INVALID/i', 'code' => TelegramErrorCode::StarsAmountInvalid, 'class' => StarsAmountInvalidException::class],
        ['status' => 400, 'pattern' => '/DATE_TOO_FAR/i', 'code' => TelegramErrorCode::DateTooFar, 'class' => DateTooFarException::class],
        ['status' => 400, 'pattern' => '/DATE_IN_PAST/i', 'code' => TelegramErrorCode::DateInPast, 'class' => DateInPastException::class],
    ];

    /**
     * Resolves the matching TelegramErrorCode for the given HTTP code and description.
     */
    public static function matchCode(int|string $httpStatus, string $description): TelegramErrorCode
    {
        $code = is_numeric($httpStatus) ? (int)$httpStatus : 0;

        if ($code === 429 || str_contains(strtolower($description), 'retry after')) {
            return TelegramErrorCode::FloodWait;
        }

        foreach (self::$rules as $rule) {
            if ($rule['status'] === $code && preg_match($rule['pattern'], $description)) {
                return $rule['code'];
            }
        }

        // Check patterns even if status code differs or is missing
        foreach (self::$rules as $rule) {
            if (preg_match($rule['pattern'], $description)) {
                return $rule['code'];
            }
        }

        return TelegramErrorCode::Unknown;
    }

    /**
     * Creates the most specific typed ApiException subclass for the given response.
     */
    public static function createException(
        string $description,
        int|string $httpStatus = 0,
        ?array $parameters = null,
        ?Throwable $previous = null
    ): ApiException {
        $code = is_numeric($httpStatus) ? (int)$httpStatus : 0;

        // Flood wait check
        if ($code === 429 || isset($parameters['retry_after']) || str_contains(strtolower($description), 'retry after')) {
            $retryAfter = (int)($parameters['retry_after'] ?? 1);
            if ($retryAfter <= 0 && preg_match('/retry after (\d+)/i', $description, $m)) {
                $retryAfter = (int)$m[1];
            }
            return new RateLimitException($description, 429, $retryAfter, $parameters, $previous);
        }

        foreach (self::$rules as $rule) {
            if ($rule['status'] === $code && preg_match($rule['pattern'], $description)) {
                $class = $rule['class'];
                return new $class($description, $code, $parameters, $previous);
            }
        }

        // Pattern matching fallback
        foreach (self::$rules as $rule) {
            if (preg_match($rule['pattern'], $description)) {
                $class = $rule['class'];
                return new $class($description, $code !== 0 ? $code : $rule['status'], $parameters, $previous);
            }
        }

        // HTTP status fallback
        return match ($code) {
            400 => new BadRequestException($description, 400, $parameters, $previous),
            401 => new UnauthorizedException($description, 401, $parameters, $previous),
            403 => new ForbiddenException($description, 403, $parameters, $previous),
            404 => new NotFoundException($description, 404, $parameters, $previous),
            409 => new ConflictException($description, 409, $parameters, $previous),
            413 => new FileTooLargeException($description, 413, $parameters, $previous),
            default => new ApiException($description, $code, $parameters, $previous, TelegramErrorCode::Unknown),
        };
    }
}
