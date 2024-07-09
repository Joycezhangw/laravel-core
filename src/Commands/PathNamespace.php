<?php

namespace LanDao\LaravelCore\Commands;

use Illuminate\Support\Str;

trait PathNamespace
{
    public function studly_path(string $path, $ds = '/'): string
    {
        return collect(explode($ds, $this->clean_path($path, $ds)))->map(fn($path) => Str::studly($path))->implode($ds);
    }

    public function studly_namespace(string $namespace, $ds = '\\'): string
    {
        return $this->studly_path($namespace, $ds);
    }

    public function path_namespace(string $path): string
    {
        return Str::of($this->studly_path($path))->replace('/', '\\')->trim('\\');
    }

    public function module_namespace(string $module, ?string $path = null): string
    {
        $module_namespace = $module !== 'App' ? 'Module' . '\\' . ($module) : 'App';
        $module_namespace .= strlen($path) ? '\\' . $this->path_namespace($path) : '';
        return $this->studly_namespace($module_namespace);
    }

    public function clean_path(string $path, $ds = '/'): string
    {
        return Str::of($path)->explode($ds)->reject(empty($path))->implode($ds);
    }

    public function app_path(?string $path = null): string
    {
        $config_path = 'app/';
        $app_path = strlen($config_path) ? trim($config_path, '/') : 'app';
        $app_path .= strlen($path) ? '/' . $path : '';

        return $this->clean_path($app_path);
    }
}
