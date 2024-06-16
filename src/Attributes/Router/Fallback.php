<?php

namespace LanDao\LaravelCore\Attributes\Router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Fallback
{
    public function __construct()
    {
    }
}
