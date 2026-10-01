<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum SuggestedPostInfoState: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
}
