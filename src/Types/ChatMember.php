<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ArrayOf;

/**
 * This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:
 * - ChatMemberOwner
 * - ChatMemberAdministrator
 * - ChatMemberMember
 * - ChatMemberRestricted
 * - ChatMemberLeft
 * - ChatMemberBanned
 *
 * @link https://core.telegram.org/bots/api#chatmember
 */
class ChatMember extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['status'] ?? '') === 'creator') return ChatMemberOwner::class;
        if (($data['status'] ?? '') === 'administrator') return ChatMemberAdministrator::class;
        if (($data['status'] ?? '') === 'member') return ChatMemberMember::class;
        if (($data['status'] ?? '') === 'restricted') return ChatMemberRestricted::class;
        if (($data['status'] ?? '') === 'left') return ChatMemberLeft::class;
        if (($data['status'] ?? '') === 'kicked') return ChatMemberBanned::class;
        return static::class;
    }
}
