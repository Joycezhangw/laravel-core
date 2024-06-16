<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;
use LanDao\LaravelCore\Attributes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class ScopeBindings implements RouteAttribute
{
    public function __construct(
        public bool $scopeBindings = true,
    )
    {
    }
}
