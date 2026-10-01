<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ChatJoinRequestResult: string
{
    case APPROVE = 'approve';
    case DECLINE = 'decline';
    case QUEUE = 'queue';
}
