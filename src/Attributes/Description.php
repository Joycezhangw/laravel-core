<?php
declare (strict_types=1);

namespace LanDao\LaravelCore\Attributes;

use Attribute;

/**
 * 说明注解
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class Description
{
    /**
     * @param string $value
     */
    public function __construct(private string $value = '')
    {
    }
}