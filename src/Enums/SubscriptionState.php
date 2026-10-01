<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum SubscriptionState: string
{
    case CANCELED = 'canceled';
    case ACTIVE = 'active';
    case FAILED = 'failed';
}
