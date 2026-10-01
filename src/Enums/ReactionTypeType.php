<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ReactionTypeType: string
{
    case EMOJI = 'emoji';
    case CUSTOM_EMOJI = 'custom_emoji';
    case PAID = 'paid';
}
