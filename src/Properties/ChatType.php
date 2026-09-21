<?php

declare(strict_types=1);

namespace Tueen\Telegram\Properties;

enum ChatType: string
{
    case Sender = 'sender';
    case Private = 'private';
    case Group = 'group';
    case Supergroup = 'supergroup';
    case Channel = 'channel';
}
