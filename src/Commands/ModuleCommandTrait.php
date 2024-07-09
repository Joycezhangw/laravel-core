<?php

namespace LanDao\LaravelCore\Commands;

use Illuminate\Support\Str;

trait ModuleCommandTrait
{
    public function getModuleName(): string
    {
        $module = $this->argument('module') ?: 'app';
        return Str::studly($module);
    }

}
