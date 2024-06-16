<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;
use LanDao\LaravelCore\Attributes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DomainFromConfig implements RouteAttribute
{
    public function __construct(
        public string $domain
    )
    {
    }
}
