<?php
declare(strict_types=1);

namespace LanDao\LaravelCore\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Inject
{
    public function __construct(protected ?string $abstract = null)
    {

    }
}