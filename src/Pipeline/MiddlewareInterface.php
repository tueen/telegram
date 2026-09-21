<?php

declare(strict_types=1);

namespace Tueen\Telegram\Pipeline;

use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;

interface MiddlewareInterface
{
    /**
     * @param callable(Request, Config): Response $next
     */
    public function handle(Request $request, Config $config, callable $next): Response;
}
