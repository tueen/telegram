<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;

/**
 * Guards a Flow or Action by requiring the user to be a member of a specified Telegram channel or group.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class RequireMember
{
    public function __construct(
        public readonly string|int $channel,
        public readonly ?string $fallbackMessage = '⚠️ You must join {channel} to unlock this feature.',
        public readonly ?string $joinUrl = null,
        public readonly string $checkButtonLabel = '🔄 Check Membership',
    ) {}
}
