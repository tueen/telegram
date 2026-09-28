<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Update;

/**
 * Use this method to receive incoming updates using long polling (wiki). Returns an Array of Update objects.
 *
 * @link https://core.telegram.org/bots/api#getupdates
 */
#[ApiMethod('getUpdates', 'POST')]
#[ReturnType(Update::class, isArray: true)]
class GetUpdates extends Method
{
    /**
     * Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as getUpdates is called with an offset higher than its update_id. The negative offset can be specified to retrieve updates starting from -offset update from the end of the updates queue. All previous updates will be forgotten.
     */
    #[Field('offset', required: false)]
    public ?int $offset = null;

    /**
     * Limits the number of updates to be retrieved. Values between 1-100 are accepted. Defaults to 100.
     */
    #[Field('limit', required: false)]
    public ?int $limit = null;

    /**
     * Timeout in seconds for long polling. Defaults to 0, i.e. usual short polling. Should be positive, short polling should be used for testing purposes only.
     */
    #[Field('timeout', required: false)]
    public ?int $timeout = null;

    /**
     * A JSON-serialized list of the update types you want your bot to receive. For example, specify ["message", "edited_channel_post", "callback_query"] to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member, message_reaction, and message_reaction_count (default). If not specified, the previous setting will be used. Please note that this parameter doesn't affect updates created before the call to getUpdates, so unwanted updates may be received for a short period of time.
     */
    #[Field('allowed_updates', required: false)]
    public ?array $allowedUpdates = null;

    public function __construct(
        ?int $offset = null,
        ?int $limit = null,
        ?int $timeout = null,
        ?array $allowedUpdates = null,
        mixed ...$extra
    )
    {
        if ($offset !== null) $this->offset = $offset;
        if ($limit !== null) $this->limit = $limit;
        if ($timeout !== null) $this->timeout = $timeout;
        if ($allowedUpdates !== null) $this->allowedUpdates = $allowedUpdates;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
