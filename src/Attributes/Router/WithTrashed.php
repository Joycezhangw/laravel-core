<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;
use LanDao\LaravelCore\Attributes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class WithTrashed implements RouteAttribute
{
    public bool $withTrashed;

    public function __construct(bool $withTrashed = true)
    {
        $this->withTrashed = $withTrashed;
    }
}

