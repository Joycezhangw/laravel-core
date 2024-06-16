<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;
use LanDao\LaravelCore\Attributes\Contracts\RouteAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Group implements RouteAttribute
{
    public function __construct(
        public ?string $prefix = null,
        public ?string $domain = null,
        public ?string $as = null,
        public ?array $where = [],
    ) {
    }
}
