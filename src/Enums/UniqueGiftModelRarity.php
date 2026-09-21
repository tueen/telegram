<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum UniqueGiftModelRarity: string
{
    case REGULAR = 'regular';
    case RARE = 'rare';
    case UNIQUE = 'unique';
}
