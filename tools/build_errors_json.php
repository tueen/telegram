<?php

declare(strict_types=1);

$extracted = json_decode(file_get_contents('tools/extracted_errors.json'), true);

// Standard error rules: [pattern (regex), enumCase, exceptionClass, httpStatus, defaultDesc]
$rules = [
    // 401 Unauthorized
    [
        'id' => 'unauthorized',
        'enum' => 'Unauthorized',
        'status' => 401,
        'pattern' => '/\b(unauthorized|invalid bot token|invalid token specified)\b/i',
        'exception' => 'UnauthorizedException',
        'description' => 'The bot token is invalid or has been revoked.',
    ],

    // 429 Flood wait / rate limit
    [
        'id' => 'flood_wait',
        'enum' => 'FloodWait',
        'status' => 429,
        'pattern' => '/too many requests:\s*retry after/i',
        'exception' => 'RateLimitException',
        'description' => 'Too Many Requests: hit flood limit, wait retry_after seconds.',
    ],

    // 409 Conflicts
    [
        'id' => 'conflict_webhook_active',
        'enum' => 'ConflictWebhookActive',
        'status' => 409,
        'pattern' => "/can't use getUpdates method while webhook is active/i",
        'exception' => 'WebhookActiveConflictException',
        'description' => "Can't use getUpdates while a webhook is active.",
    ],
    [
        'id' => 'conflict_terminated_by_other_get_updates',
        'enum' => 'ConflictTerminatedByOtherGetUpdates',
        'status' => 409,
        'pattern' => '/terminated by other getUpdates request/i',
        'exception' => 'TerminatedByOtherGetUpdatesException',
        'description' => 'Terminated by another getUpdates request (multiple bot instances running).',
    ],

    // 413 File too large
    [
        'id' => 'file_too_large',
        'enum' => 'FileTooLarge',
        'status' => 413,
        'pattern' => '/(request entity too large|file is too big|file exceeds the size limit)/i',
        'exception' => 'FileTooLargeException',
        'description' => 'Uploaded file exceeds the maximum size limit.',
    ],

    // 403 Forbidden
    [
        'id' => 'bot_blocked',
        'enum' => 'BotBlocked',
        'status' => 403,
        'pattern' => '/bot was blocked by the user/i',
        'exception' => 'BotBlockedException',
        'description' => 'The bot was blocked by the user.',
    ],
    [
        'id' => 'bot_kicked',
        'enum' => 'BotKicked',
        'status' => 403,
        'pattern' => '/(bot was kicked|bot is not a member)/i',
        'exception' => 'BotKickedException',
        'description' => 'The bot is not a member of the chat or was kicked.',
    ],
    [
        'id' => 'user_deactivated',
        'enum' => 'UserDeactivated',
        'status' => 403,
        'pattern' => '/user is deactivated/i',
        'exception' => 'UserDeactivatedException',
        'description' => 'The target user account was deleted or deactivated.',
    ],
    [
        'id' => 'bot_cant_initiate_conversation',
        'enum' => 'BotCantInitiateConversation',
        'status' => 403,
        'pattern' => "/bot can't initiate conversation with a user/i",
        'exception' => 'BotCantInitiateConversationException',
        'description' => "Bot can't initiate conversation with a user who hasn't started the bot.",
    ],
    [
        'id' => 'business_connection_revoked',
        'enum' => 'BusinessConnectionRevoked',
        'status' => 403,
        'pattern' => '/business connection revoked/i',
        'exception' => 'BusinessConnectionRevokedException',
        'description' => 'The business connection was revoked by the user.',
    ],
    [
        'id' => 'not_enough_rights',
        'enum' => 'NotEnoughRights',
        'status' => 403,
        'pattern' => '/(not enough rights|bot is not an administrator)/i',
        'exception' => 'NotEnoughRightsException',
        'description' => 'The bot lacks required administrative rights or permissions.',
    ],

    // 400 Bad Request: Chat & User
    [
        'id' => 'chat_not_found',
        'enum' => 'ChatNotFound',
        'status' => 400,
        'pattern' => '/chat not found/i',
        'exception' => 'ChatNotFoundException',
        'description' => 'The specified chat_id was not found or is inaccessible.',
    ],
    [
        'id' => 'user_not_found',
        'enum' => 'UserNotFound',
        'status' => 400,
        'pattern' => '/user not found/i',
        'exception' => 'UserNotFoundException',
        'description' => 'The specified user was not found.',
    ],
    [
        'id' => 'user_already_participant',
        'enum' => 'UserAlreadyParticipant',
        'status' => 400,
        'pattern' => '/USER_ALREADY_PARTICIPANT/i',
        'exception' => 'UserAlreadyParticipantException',
        'description' => 'The user is already a member of the chat.',
    ],
    [
        'id' => 'user_cant_be_verified',
        'enum' => 'UserCantBeVerified',
        'status' => 400,
        'pattern' => "/user can't be verified/i",
        'exception' => 'UserCantBeVerifiedException',
        'description' => 'The user cannot be verified.',
    ],

    // 400 Bad Request: Messages
    [
        'id' => 'message_not_modified',
        'enum' => 'MessageNotModified',
        'status' => 400,
        'pattern' => '/message is not modified/i',
        'exception' => 'MessageNotModifiedException',
        'description' => 'Message content and reply markup are identical to current content.',
    ],
    [
        'id' => 'message_not_found',
        'enum' => 'MessageNotFound',
        'status' => 400,
        'pattern' => '/message( to delete| to forward)? not found/i',
        'exception' => 'MessageNotFoundException',
        'description' => 'The specified message was not found.',
    ],
    [
        'id' => 'message_cant_be_deleted',
        'enum' => 'MessageCantBeDeleted',
        'status' => 400,
        'pattern' => "/message can't be deleted/i",
        'exception' => 'MessageCantBeDeletedException',
        'description' => 'Message cannot be deleted (e.g. 48-hour limit expired or no rights).',
    ],
    [
        'id' => 'message_cant_be_edited',
        'enum' => 'MessageCantBeEdited',
        'status' => 400,
        'pattern' => "/message can't be edited/i",
        'exception' => 'MessageCantBeEditedException',
        'description' => 'Message cannot be edited.',
    ],
    [
        'id' => 'message_empty',
        'enum' => 'MessageEmpty',
        'status' => 400,
        'pattern' => '/(message is empty|message text is empty)/i',
        'exception' => 'MessageEmptyException',
        'description' => 'Message text or content is empty.',
    ],
    [
        'id' => 'message_too_long',
        'enum' => 'MessageTooLong',
        'status' => 400,
        'pattern' => '/(TEXT_TOO_LONG|message is too long)/i',
        'exception' => 'MessageTooLongException',
        'description' => 'Message text exceeds the maximum character limit.',
    ],
    [
        'id' => 'cant_parse_entities',
        'enum' => 'CantParseEntities',
        'status' => 400,
        'pattern' => "/(can't parse entities|ENTITY_)/i",
        'exception' => 'CantParseEntitiesException',
        'description' => "Failed to parse text formatting entities with current parse_mode.",
    ],
    [
        'id' => 'reply_message_not_found',
        'enum' => 'ReplyMessageNotFound',
        'status' => 400,
        'pattern' => '/REPLY_MESSAGE_NOT_FOUND/i',
        'exception' => 'ReplyMessageNotFoundException',
        'description' => 'The message specified in reply_parameters was not found.',
    ],
    [
        'id' => 'message_id_invalid',
        'enum' => 'MessageIdInvalid',
        'status' => 400,
        'pattern' => '/MESSAGE_ID_INVALID/i',
        'exception' => 'MessageIdInvalidException',
        'description' => 'The message_id specified is invalid.',
    ],

    // 400 Bad Request: Topics & Forums
    [
        'id' => 'topic_not_modified',
        'enum' => 'TopicNotModified',
        'status' => 400,
        'pattern' => '/TOPIC_NOT_MODIFIED/i',
        'exception' => 'TopicNotModifiedException',
        'description' => 'Topic name or icon is unchanged.',
    ],
    [
        'id' => 'topic_closed',
        'enum' => 'TopicClosed',
        'status' => 400,
        'pattern' => '/TOPIC_CLOSED/i',
        'exception' => 'TopicClosedException',
        'description' => 'The topic is already closed.',
    ],
    [
        'id' => 'topic_deleted',
        'enum' => 'TopicDeleted',
        'status' => 400,
        'pattern' => '/TOPIC_DELETED/i',
        'exception' => 'TopicDeletedException',
        'description' => 'The forum topic was deleted.',
    ],

    // 400 Bad Request: Media & Files
    [
        'id' => 'wrong_file_type',
        'enum' => 'WrongFileType',
        'status' => 400,
        'pattern' => '/(wrong file type|FILE_PARTS_INVALID)/i',
        'exception' => 'WrongFileTypeException',
        'description' => 'The file format or MIME type is not accepted for this method.',
    ],
    [
        'id' => 'photo_invalid_dimensions',
        'enum' => 'PhotoInvalidDimensions',
        'status' => 400,
        'pattern' => '/PHOTO_INVALID_DIMENSIONS/i',
        'exception' => 'PhotoInvalidDimensionsException',
        'description' => 'Photo dimensions exceed or violate allowed aspect ratio.',
    ],
    [
        'id' => 'voice_messages_forbidden',
        'enum' => 'VoiceMessagesForbidden',
        'status' => 400,
        'pattern' => '/VOICE_MESSAGES_FORBIDDEN/i',
        'exception' => 'VoiceMessagesForbiddenException',
        'description' => 'The recipient has disabled voice messages in their privacy settings.',
    ],
    [
        'id' => 'media_empty',
        'enum' => 'MediaEmpty',
        'status' => 400,
        'pattern' => '/MEDIA_EMPTY/i',
        'exception' => 'MediaEmptyException',
        'description' => 'The media container or media array is empty.',
    ],

    // 400 Bad Request: Keyboards & Buttons
    [
        'id' => 'button_data_invalid',
        'enum' => 'ButtonDataInvalid',
        'status' => 400,
        'pattern' => '/BUTTON_DATA_INVALID/i',
        'exception' => 'ButtonDataInvalidException',
        'description' => 'Callback button data is invalid or exceeds 64 bytes.',
    ],
    [
        'id' => 'button_url_invalid',
        'enum' => 'ButtonUrlInvalid',
        'status' => 400,
        'pattern' => '/BUTTON_URL_INVALID/i',
        'exception' => 'ButtonUrlInvalidException',
        'description' => 'The URL provided in an inline button is invalid.',
    ],
    [
        'id' => 'reply_markup_invalid',
        'enum' => 'ReplyMarkupInvalid',
        'status' => 400,
        'pattern' => '/REPLY_MARKUP_INVALID/i',
        'exception' => 'ReplyMarkupInvalidException',
        'description' => 'The reply_markup JSON structure is invalid.',
    ],

    // 400 Bad Request: Commands
    [
        'id' => 'commands_list_empty',
        'enum' => 'CommandsListEmpty',
        'status' => 400,
        'pattern' => '/commands list must be non-empty/i',
        'exception' => 'CommandsListEmptyException',
        'description' => 'Passing empty commands array; use deleteMyCommands instead.',
    ],
    [
        'id' => 'too_many_commands',
        'enum' => 'TooManyCommands',
        'status' => 400,
        'pattern' => '/too many commands/i',
        'exception' => 'TooManyCommandsException',
        'description' => 'Exceeded the limit of 100 commands.',
    ],
    [
        'id' => 'command_too_long',
        'enum' => 'CommandTooLong',
        'status' => 400,
        'pattern' => '/command is too long/i',
        'exception' => 'CommandTooLongException',
        'description' => 'Command name exceeds 32 chars or description exceeds 256 chars.',
    ],
    [
        'id' => 'command_invalid',
        'enum' => 'CommandInvalid',
        'status' => 400,
        'pattern' => '/command must start with a letter/i',
        'exception' => 'CommandInvalidException',
        'description' => 'Command names must start with a letter and contain only a-z, 0-9, and _.',
    ],
    [
        'id' => 'invalid_language_code',
        'enum' => 'InvalidLanguageCode',
        'status' => 400,
        'pattern' => '/language_code must be a 2-letter ISO 639-1 code/i',
        'exception' => 'InvalidLanguageCodeException',
        'description' => 'Invalid language code; must be 2-letter ISO 639-1.',
    ],

    // 400 Bad Request: Queries & WebApps
    [
        'id' => 'query_id_invalid',
        'enum' => 'QueryIdInvalid',
        'status' => 400,
        'pattern' => '/(query is too old|QUERY_ID_INVALID)/i',
        'exception' => 'QueryIdInvalidException',
        'description' => 'Query is too old or query_id is invalid.',
    ],
    [
        'id' => 'business_connection_not_found',
        'enum' => 'BusinessConnectionNotFound',
        'status' => 400,
        'pattern' => '/business_connection_id not found/i',
        'exception' => 'BusinessConnectionNotFoundException',
        'description' => 'The specified business_connection_id was not found.',
    ],

    // 400 Bad Request: Stickers
    [
        'id' => 'stickerset_invalid',
        'enum' => 'StickerSetInvalid',
        'status' => 400,
        'pattern' => '/STICKERSET_INVALID/i',
        'exception' => 'StickerSetInvalidException',
        'description' => 'The sticker set is invalid or does not exist.',
    ],
    [
        'id' => 'sticker_emoji_invalid',
        'enum' => 'StickerEmojiInvalid',
        'status' => 400,
        'pattern' => '/STICKER_EMOJI_INVALID/i',
        'exception' => 'StickerEmojiInvalidException',
        'description' => 'The emoji provided for the sticker is invalid.',
    ],
    [
        'id' => 'sticker_dimensions_invalid',
        'enum' => 'StickerDimensionsInvalid',
        'status' => 400,
        'pattern' => '/(STICKER_PNG_DIMENSIONS|STICKER_TGS_DIMENSIONS)/i',
        'exception' => 'StickerDimensionsInvalidException',
        'description' => 'Sticker dimensions must be exactly 512x512 pixels.',
    ],

    // 400 Bad Request: Stars & Payments
    [
        'id' => 'stars_amount_invalid',
        'enum' => 'StarsAmountInvalid',
        'status' => 400,
        'pattern' => '/STARS_AMOUNT_INVALID/i',
        'exception' => 'StarsAmountInvalidException',
        'description' => 'Invalid amount of Telegram Stars (must be between 1 and 25000).',
    ],
    [
        'id' => 'date_too_far',
        'enum' => 'DateTooFar',
        'status' => 400,
        'pattern' => '/DATE_TOO_FAR/i',
        'exception' => 'DateTooFarException',
        'description' => 'Scheduled date is too far in the future (max 30 days).',
    ],
    [
        'id' => 'date_in_past',
        'enum' => 'DateInPast',
        'status' => 400,
        'pattern' => '/DATE_IN_PAST/i',
        'exception' => 'DateInPastException',
        'description' => 'Scheduled date cannot be in the past.',
    ],
];

// Now match every extracted error to the rules and associate methods
$catalog = [];
foreach ($rules as $r) {
    $catalog[$r['id']] = [
        'id' => $r['id'],
        'enum' => $r['enum'],
        'status' => $r['status'],
        'pattern' => $r['pattern'],
        'exception' => $r['exception'],
        'description' => $r['description'],
        'methods' => [],
    ];
}

// For each method and each error row, map to rule
$methodErrorMap = [];

foreach ($extracted as $method => $markdown) {
    $lines = explode("\n", $markdown);
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/^\|\s*(\d{3})\s*\|\s*`?([^`|]+)`?\s*\|\s*([^|]+)\|/i', $line, $m)) {
            $code = (int)$m[1];
            $error = trim($m[2]);
            $cause = trim($m[3]);

            $matchedRuleId = null;
            foreach ($rules as $r) {
                if ($code === $r['status'] && preg_match($r['pattern'], $error)) {
                    $matchedRuleId = $r['id'];
                    break;
                }
            }

            if ($matchedRuleId) {
                if (!in_array($method, $catalog[$matchedRuleId]['methods'], true)) {
                    $catalog[$matchedRuleId]['methods'][] = $method;
                }
                if (!in_array($matchedRuleId, $methodErrorMap[$method] ?? [], true)) {
                    $methodErrorMap[$method][] = $matchedRuleId;
                }
            }
        }
    }
}

$output = [
    'version' => '1.0.0',
    'errors' => $catalog,
    'methods' => $methodErrorMap,
];

file_put_contents('resources/errors.json', json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
echo "Successfully generated resources/errors.json with " . count($catalog) . " error rules and " . count($methodErrorMap) . " mapped methods.\n";
