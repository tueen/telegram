<?php

namespace Tueen\Telegram\Enums;

enum SubscriptionState: string
{
    case CANCELED = 'canceled';
    case ACTIVE = 'active';
    case FAILED = 'failed';
}
