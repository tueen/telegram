<?php

namespace Tueen\Telegram\Enums;

enum RevenueWithdrawalStateType: string
{
    case PENDING = 'pending';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
}
