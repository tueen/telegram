<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

/**
 * Backed enum representing all Telegram Bot API 10.3 Update types.
 */
enum UpdateType: string
{
    case MESSAGE = 'message';
    case EDITED_MESSAGE = 'edited_message';
    case CHANNEL_POST = 'channel_post';
    case EDITED_CHANNEL_POST = 'edited_channel_post';
    case BUSINESS_CONNECTION = 'business_connection';
    case BUSINESS_MESSAGE = 'business_message';
    case EDITED_BUSINESS_MESSAGE = 'edited_business_message';
    case DELETED_BUSINESS_MESSAGES = 'deleted_business_messages';
    case MESSAGE_REACTION = 'message_reaction';
    case MESSAGE_REACTION_COUNT = 'message_reaction_count';
    case INLINE_QUERY = 'inline_query';
    case CHOSEN_INLINE_RESULT = 'chosen_inline_result';
    case CALLBACK_QUERY = 'callback_query';
    case SHIPPING_QUERY = 'shipping_query';
    case PRE_CHECKOUT_QUERY = 'pre_checkout_query';
    case PURCHASED_PAID_MEDIA = 'purchased_paid_media';
    case POLL = 'poll';
    case POLL_ANSWER = 'poll_answer';
    case MY_CHAT_MEMBER = 'my_chat_member';
    case CHAT_MEMBER = 'chat_member';
    case CHAT_JOIN_REQUEST = 'chat_join_request';
    case CHAT_BOOST = 'chat_boost';
    case REMOVED_CHAT_BOOST = 'removed_chat_boost';
    case MANAGED_BOT = 'managed_bot';
    case BOT_SUBSCRIPTION = 'bot_subscription';
    case MESSAGE_GENERATION_STOPPED = 'message_generation_stopped';
    case UNKNOWN = 'unknown';
}
