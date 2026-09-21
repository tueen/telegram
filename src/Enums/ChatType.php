<?php

namespace Tueen\Telegram\Enums;

enum ChatType: string
{
    case SENDER = 'sender';
    case PRIVATE = 'private';
    case GROUP = 'group';
    case SUPERGROUP = 'supergroup';
    case CHANNEL = 'channel';
}
