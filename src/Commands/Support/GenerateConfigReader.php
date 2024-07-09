<?php

namespace LanDao\LaravelCore\Commands\Support;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("landao.generator.paths.$value"));
    }

}
