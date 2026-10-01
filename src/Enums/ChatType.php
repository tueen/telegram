<?php

declare(strict_types=1);

namespace Tueen\Telegram\Enums;

enum ChatType: string
{
    case SENDER = 'sender';
    case PRIVATE = 'private';
    case GROUP = 'group';
    case SUPERGROUP = 'supergroup';
    case CHANNEL = 'channel';
}
