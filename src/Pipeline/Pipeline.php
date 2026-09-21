<?php

declare(strict_types=1);

namespace Tueen\Telegram\Pipeline;

use Closure;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Client\Response;
use Tueen\Telegram\Config;

class Pipeline
{
    /**
     * @var array<MiddlewareInterface|Closure>
     */
    private array $middlewares = [];

    /**
     * Appends a middleware to the pipeline.
     */
    public function pipe(MiddlewareInterface|Closure $middleware): static
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Runs the pipeline ending with the destination callable.
     *
     * @param callable(Request, Config): Response $destination
     */
    public function run(Request $request, Config $config, callable $destination): Response
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            function (callable $next, MiddlewareInterface|Closure $middleware) {
                return function (Request $request, Config $config) use ($next, $middleware): Response {
                    if ($middleware instanceof MiddlewareInterface) {
                        return $middleware->handle($request, $config, $next);
                    }
                    return $middleware($request, $config, $next);
                };
            },
            $destination
        );

        return $pipeline($request, $config);
    }
}
