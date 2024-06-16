<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;
use LanDao\LaravelCore\Attributes\Contracts\WhereAttribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Where implements WhereAttribute
{
    public function __construct(
        public string $param,
        public string $constraint,
    )
    {

    }
}
