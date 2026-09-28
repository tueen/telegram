<?php

declare(strict_types=1);

namespace Tueen\Telegram\Running;

use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;

interface RunningModeInterface
{
    /**
     * Obtains or processes an incoming update or continuous polling stream.
     *
     * @param callable(Update): mixed|null $handler
     */
    public function processUpdate(Telegram $bot, ?callable $handler = null): mixed;
}
