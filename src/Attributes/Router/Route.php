<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use LanDao\LaravelCore\Attributes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route implements RouteAttribute
{
    public array|string $methods;

    public array|string $middleware;

    public function __construct(
        array|string   $methods,
        public string  $uri,
        public ?string $name = null,
        array|string   $middleware = [],
    )
    {
        $this->methods = array_map(
            static fn(string $verb) => in_array(
                $upperVerb = strtoupper($verb),
                Router::$verbs
            )
                ? $upperVerb
                : $verb,
            Arr::wrap($methods)
        );
        $this->middleware = Arr::wrap($middleware);
    }
}
